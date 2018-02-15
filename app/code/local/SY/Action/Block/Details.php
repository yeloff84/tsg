<?php

/**
 * Class Sy_Action_Block_Details
 */
class Sy_Action_Block_Details extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @param $actionId
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getActionProducts($actionId)
    {
        /* @var $products Mage_Catalog_Model_Resource_Product_Collection */
        $products =  Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->joinTable('action/products', 'product_id=entity_id', array('aid' => 'action_id'))
            ->addFieldToFilter('aid', $actionId)
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

        return $products;
    }

    /**
     * @return Zend_Controller_Response_Abstract
     * @throws Exception
     */
    public function getActionDetails()
    {
        $actionId = $this->getRequest()->getParam('id');

        /** @var Mage_Core_Model_Date $datemodel */
        $datemodel = Mage::getModel('core/date');
        $curdate = $datemodel->gmtDate('Y-m-d H:i:s');


        $action = Mage::getModel('action/action')
            ->getCollection()
            ->addFieldToFilter('id', $actionId)
            ->addFieldToFilter('end_datetime', array(
                array('gteq' => $curdate),
                array('null' => true)
            ))
            ->addFieldToFilter('is_active', 1)
            ->getFirstItem();

        if ($action->getData()) {

            return $action;

        } else {

            return Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl().'actions/index/index');

            exit();
        }
    }
}
