<?php

/**
 * Class Tsg_Imgimport_Adminhtml_ImgimportController
 */

class Tsg_Imgimport_Adminhtml_ImgimportController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Display csv file upload form
     * @return void
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     * @throws Exception
     */
    public function importAction()
    {
        /** @var  Tsg_Imgimport_Helper_Data $helper */
        $helper = Mage::helper('tsg_imgimport');
        if (isset($_FILES['csv']['name']) and (file_exists($_FILES['csv']['tmp_name']))) {
            try {
                $uploader = new Varien_File_Uploader('csv');
                $uploader->setAllowedExtensions(array('csv'));
                $uploader->setAllowRenameFiles(true);
                $name = mktime() . '.csv';
                $path = $helper->getBaseUploadPath();
                $uploader->save($path, $name);
                $file = $path.$name;
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        /** @var Tsg_Imgimport_Model_Imgimport $model */
        $model = Mage::getModel('tsg_imgimport/imgimport');

        /** @var Mage_Core_Model_Session $session */
        $session = Mage::getSingleton('core/session');
        $csvData = $helper->getCsvData($file);
        if ($csvData) {
            $skuKey = array_search('sku', $csvData[0]);
            $urlKey = array_search('url', $csvData[0]);
            array_shift($csvData);
            if ($skuKey !== false && $urlKey !== false) {
                $i = 0;
                $dbErrors = 0;
                foreach ($csvData as $row => $r) {
                    $model->setSku($r[$skuKey]);
                    $model->setUrl($r[$urlKey]);
                    try {
                        $model->save();
                        $model->unsetData();
                        $i++;
                    } catch (Exception $e) {
                        Mage::logException($e);
                        $dbErrors++;
                    }
                }
                $session->addSuccess($helper->__($i . ' rows have imported successfully'));
                if ($dbErrors > 0) {
                    $session->addError($helper->__($dbErrors . 'rows failed'));
                }
            } else {
                $session->addError('Your CSV is invalid');
            }
        }
        return $this->_redirect('*/*/index');
    }

    /**
     * Add imports rows grid to tab layout
     */
    public function tabAction()
    {
        $resp = $this->getLayout()->createBlock('tsg_imgimport/adminhtml_tabs_imgimport')->toHtml();
        $this->getResponse()->setBody($resp);
    }

    /**
     * Imports rows grid actions
     */
    public function importsgridAction()
    {
        return $this->tabAction();
    }
}
