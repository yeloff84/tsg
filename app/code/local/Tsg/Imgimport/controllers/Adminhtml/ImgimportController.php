<?php

/**
 * Class Tsg_Imgimport_Adminhtml_ImgimportController
 */
class Tsg_Imgimport_Adminhtml_ImgimportController extends Mage_Adminhtml_Controller_Action
{
    /**
     *
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

                $name = $_FILES['csv']['name'];

                $path = $helper->getBaseUploadPath();

                $uploader->save($path, $name);

                $file = $path.$name;
            } catch (Exception $e) {
                Mage::log($e->getMessage());
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
                foreach ($csvData as $row => $r) {
                    $model->setSku($r[$skuKey]);
                    $model->setUrl($r[$urlKey]);
                    $model->save();
                    $model->unsetData();
                    $i++;
                }
                $session->addSuccess($i . ' rows have imported successfully');

                if (!empty($dbErrors)) {
                    $session->addError(count($dbErrors) . 'rows failed');
                }
            } else {
                $session->addError('Your CSV is invalid');
            }
        }

        return $this->_redirect('*/*/index');
    }

    /**
     * @throws Mage_Core_Exception
     */
    public function tabAction()
    {
        $resp = $this->getLayout()->createBlock('tsg_imgimport/adminhtml_tabs_imgimport')->toHtml();
        $this->getResponse()->setBody($resp);
    }

    /**
     * @throws Mage_Core_Exception
     */
    public function importsgridAction()
    {
        return $this->tabAction();
    }
}
