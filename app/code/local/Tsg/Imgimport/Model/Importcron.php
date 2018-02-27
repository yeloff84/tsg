<?php

/**
 * Class Tsg_Imgimport_Model_Importcron
 */

class Tsg_Imgimport_Model_Importcron
{
    /**
     * @param Mage_Cron_Model_Schedule $schedule
     * @return $this
     */
    public function crontask(Mage_Cron_Model_Schedule $schedule, $status = Tsg_Imgimport_Model_Imgimport::INQUEUE)
    {
        /** @var Tsg_Imgimport_Model_Imgimport $model */
        $model = Mage::getModel('tsg_imgimport/imgimport');

        /** @var Mage_Catalog_Model_Product $pmodel */
        $pmodel = Mage::getModel('catalog/product');

        /** @var Tsg_Imgimport_Helper_Data $helper */
        $helper = Mage::helper('tsg_imgimport');

        /** @var Mage_Core_Model_Date $datemodel */
        $dmodel = Mage::getModel('core/date');

        $items = $model->getCollection()->addFieldToFilter('status', $status);

        foreach ($items as $item) {
            $curdate = $dmodel->gmtDate('Y-m-d H:i:s');
            $mimeTypes = array('image/jpeg', 'image/png', 'image/gif');
            try {
                $imageUrl = str_replace(' ', '%20', $item->getUrl());
                $curl = new Varien_Http_Adapter_Curl();
                $curl->setConfig([
                    'timeout' => 15,
                    'header'  => false
                ]);

                $curl->write(Zend_Http_Client::GET, $imageUrl, '1.0');
                $data = $curl->read();
                $httpCode = $curl->getInfo(CURLINFO_HTTP_CODE);
                $contentType = $curl->getInfo(CURLINFO_CONTENT_TYPE);
                $curl->close();

                $filePath = '';
                $imageType = substr(strrchr($imageUrl, "."), 1);
                $path = Mage::getBaseDir('media') . DS . 'import';
                $fileName = mktime() . '.' . $imageType;
                $file = new Varien_Io_File();
                $file->setAllowCreateFolders(true);
                $file->open(array('path' => $path));
                if ($file->write($fileName, $data)) {
                    $filePath = $path . DS . $fileName;
                }
                if ($httpCode == '404') {
                    $model->load($item->getId())->addData([
                        'status' => $model::RETRY,
                        'upload_datetime' => $curdate,
                        'error' => '404 Retry'
                    ]);
                } elseif ($httpCode == '200' && $filePath != '') {
                    /** @var Mage_Catalog_Model_Product $product */
                    $product = $pmodel->loadByAttribute('sku', $item->getSku());
                    if ($product != false) {
                        $productId = $product->getEntityId();
                        $isUnique = $helper->isUniqueImg($productId, $filePath);
                        if (!in_array($contentType, $mimeTypes)) {
                            $model->load($item->getId())->addData([
                                'status' => $model::ERROR,
                                'upload_datetime' => $curdate,
                                'error' => 'Disallowed mime type'
                            ]);
                        } elseif ($isUnique == false) {
                            $model->load($item->getId())->addData([
                                'status' => $model::ERROR,
                                'upload_datetime' => $curdate,
                                'error' => 'Duplicated value'
                            ]);
                        } else {
                            $fileSize = filesize($filePath);

                            $mediaAttributes = array();

                            if (!$product->getImage() || $product->getImage() == 'no_selection') {
                                $mediaAttribute[] = 'image';
                            }
                            if (!$product->getSmallImage() || $product->getSmallImage() == 'no_selection') {
                                $mediaAttribute[] = 'small_image';
                            }
                            if (!$product->getThumbnail() || $product->getThumbnail() == 'no_selection') {
                                $mediaAttribute[] = 'thumbnail';
                            }

                            $product->addImageToMediaGallery($filePath, $mediaAttributes, false, false);
                            $product->save();
                            $model->load($item->getId())->addData([
                                'status' => $model::UPLOADED,
                                'upload_datetime' => $curdate,
                                'img_size' => $fileSize,
                                'error' => ''
                            ]);
                        }
                    } else {
                        $model->load($item->getId())->addData([
                            'status' => $model::ERROR,
                            'upload_datetime' => $curdate,
                            'error' => 'Product not found'
                        ]);
                    }
                } else {
                    $model->load($item->getId())->addData([
                        'status' => $model::ERROR,
                        'upload_datetime' => $curdate,
                        'error' => 'Server not found'
                    ]);
                }
                $model->save();
                $model->unsetData();
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        return $this;
    }

    /**
     * @param Mage_Cron_Model_Schedule $schedule
     * @return Tsg_Imgimport_Model_Importcron
     */
    public function retrytask(Mage_Cron_Model_Schedule $schedule)
    {
        return $this->crontask($schedule, Tsg_Imgimport_Model_Imgimport::RETRY);
    }
}
