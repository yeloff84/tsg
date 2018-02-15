<?php

/**
 * Class Sy_Action_Adminhtml_ActionsController
 */
class Sy_Action_Adminhtml_ActionsController extends Mage_Adminhtml_Controller_Action
{
    /**
     *
     */
    public function indexAction()
    {
        $this->loadLayout();

        $this->_setActiveMenu('sy_action');

        $this->renderLayout();
    }

    /**
     *
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * @throws Mage_Core_Exception
     */
    public function editAction()
    {
        $actionId = (int) $this->getRequest()->getParam('id');

        $model = Mage::getModel('action/action')->load($actionId);

        Mage::register('current_action', $model);

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @throws Mage_Core_Exception
     */
    public function productsTabAction()
    {
        $actionId = (int) $this->getRequest()->getParam('id');

        $model = Mage::getModel('action/action')->load($actionId);

        Mage::register('current_action', $model);

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @throws Mage_Core_Exception
     */
    public function productsGridAction()
    {
        $actionId = (int) $this->getRequest()->getParam('id');

        $model = Mage::getModel('action/action')->load($actionId);

        Mage::register('current_action', $model);

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     *
     */
    public function saveAction()
    {

        if ($productsIds = $this->getRequest()->getParam('products_ids', null)) {
            $productsIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($productsIds);
        }

        $actionId = $this->getRequest()->getParam('id');

        $model = null;

        if ($actionId) {
            $model = Mage::getModel('action/action')->load($actionId);
        } else {
            $model = Mage::getModel('action/action');
        }

        $data = $this->getRequest()->getPost();

        if (!array_key_exists('is_active', $data)) {
            $data['is_active'] = 0;
        }

        /** @var Mage_Core_Model_Date $timemodel */
        $timemodel = Mage::getModel('core/date');

        $data['create_datetime'] = $timemodel->gmtDate('Y-m-d H:i:s');
        $data['start_datetime'] = $timemodel->gmtDate('Y-m-d H:i:s', strtotime($data['start_datetime']));

        if (array_key_exists('end_datetime', $data)) {
            $data['end_datetime'] = $timemodel->gmtDate('Y-m-d H:i:s', strtotime($data['end_datetime']));
        }

        if (isset($_FILES['image']['name']) and (file_exists($_FILES['image']['tmp_name']))) {
            try {
                $uploader = new Varien_File_Uploader('image');
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));

                $uploader->setAllowRenameFiles(true);

                $name = $_FILES['image']['name'];

                /** @var  SY_Action_Helper_Data $helper */
                $helper = Mage::helper('sy_action');

                /** @var $path SY_Action_Helper_Data */
                $path = $helper->getBaseUploadPath();


                $uploader->save($path, $name);

                $data['image'] = $name;

            } catch(Exception $e) {

            }
        } else {
            if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                $data['image'] = '';
            } else {
                unset($data['image']);
            }
        }

        $model->addData($data);

        $backToEdit = (bool)$this->getRequest()->getParam('save_and_continue');
        $success = false;
        try {
            //$model->validate();

            $model->save();

            if ($actionId == 0) {
                $actionId = $model->getId();
            }

            $this->saveProductsAction($actionId, $data['products_ids']);

            $this->_getSession()->addSuccess($this->__('Action was saved successfully'));
            $success = true;

        }  catch (Exception $e) {
            $this->_getSession()->addError($this->__('Error occurred while saving action'));
            Mage::logException($e);
        }

        if (!$success || $backToEdit) {
            $this->_redirect('*/*/edit', array('id' => $model->getId()));
        } else {
            $this->_redirect('*/*/index');
        }
    }

    /**
     * @param $actionId
     * @param $productsIds
     * @return $this
     * @throws Exception
     */
    public function saveProductsAction($actionId, $productsIds)
    {
        $pmodel = Mage::getModel('action/products');

        $collection = $pmodel->getCollection()->addFieldToFilter('action_id', $actionId);

        /** @var SY_Action_Model_Products $collection */
        foreach ($collection as $col) {
            $pmodel->setId($col->getId())->delete();
        }
        if (!empty($productsIds)) {
            $productsIds = explode('&', $productsIds);
            $productsIds = array_filter($productsIds, 'is_numeric');
            foreach ($productsIds as $pid) {
                    $pmodel->setData(array('action_id' => $actionId, 'product_id' => $pid));
                    $pmodel->save();
                    $pmodel->unsetData();
                }
        }

        return $this;
    }

    /**
     * delete action
     *
     */
    public function deleteAction()
    {
        $pmodel = Mage::getModel('action/products');

        /** @var SY_Action_Model_Products $collection */

        if ($actionId = $this->getRequest()->getParam('id')) {

            $collection = $pmodel->getCollection()->addFieldToFilter('action_id', $actionId);

            try {
                Mage::getModel('action/action')->setId($actionId)->delete();

                foreach ($collection as $col) {
                    $pmodel->setId($col->getId())->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Action was deleted successfully'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $actionId));
            }
        }
        $this->_redirect('*/*/');
    }


    /**
     *
     */
    public function massDeleteAction()
    {
        $actions = $this->getRequest()->getParam('action', null);
        $pmodel = Mage::getModel('action/products');

        if (is_array($actions) && sizeof($actions) > 0) {
            try {
                foreach ($actions as $actionId) {
                    Mage::getModel('action/action')->setId($actionId)->delete();
                    $collection = $pmodel->getCollection()->addFieldToFilter('action_id', $actionId);
                    foreach ($collection as $col) {
                        $pmodel->setId($col->getId())->delete();
                    }
                }
                $this->_getSession()->addSuccess($this->__('Total of %d actions have been deleted', sizeof($actions)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $this->_getSession()->addError($this->__('Please select action'));
        }
        $this->_redirect('*/*');
    }
}
