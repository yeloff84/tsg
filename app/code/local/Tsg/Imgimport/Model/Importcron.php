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
    public function crontask(Mage_Cron_Model_Schedule $schedule, $status = 1)
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
            $mimetypes = array('image/jpeg', 'image/png', 'image/gif');
            try {
                $image_url = $item->getUrl();

                $curl = new Varien_Http_Adapter_Curl();
                $curl->setConfig([
                    'timeout' => 15,
                    'header'  => false
                ]);

                $image_type = substr(strrchr($image_url, "."), 1);
                $filename = md5($image_url . $item->getSku()) . '.' . $image_type;
                $filepath = Mage::getBaseDir('media') . DS . 'import' . DS . $filename;

                $curl->write(Zend_Http_Client::GET, $image_url, '1.0');
                $data = $curl->read();
                $httpcode = $curl->getInfo(CURLINFO_HTTP_CODE);
                $contenttype = $curl->getInfo(CURLINFO_CONTENT_TYPE);

                $curl->close();

                if ($httpcode == '200' && in_array($contenttype, $mimetypes)) {
                    $fileSize = filesize($filepath);

                    /** @var Mage_Catalog_Model_Product $product */
                    $product = $pmodel->loadByAttribute('sku', $item->getSku());

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

                    $product->addImageToMediaGallery($filepath, $mediaAttributes, false, false);
                    $product->save();
                    $status = 2;
                    $model->load($item->getId())->addData([
                        'status' => $status,
                        'upload_datetime' => $curdate,
                        'img_size' => $fileSize
                    ]);
                } elseif ($httpcode == '200' && !in_array($contenttype, $mimetypes)) {
                    $model->load($item->getId())->addData([
                        'status' => 4,
                        'upload_datetime' => $curdate,
                        'error' => 'Disallowed mime type'
                    ]);
                } elseif ($httpcode == '404') {
                    $model->load($item->getId())->addData([
                        'status' => 3,
                        'upload_datetime' => $curdate
                    ]);
                } else {
                    $model->load($item->getId())->addData([
                        'status' => 4,
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
        return $this->crontask($schedule, 3);
    }
}
