<?php

/**
 * Class Sy_Action_Block_Details
 *
 */
class Sy_Action_Block_Details extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     * @throws Exception
     */
    public function getActionProducts()
    {
        $actionId = $this->getRequest()->getParam('id');
        $websiteIds = array(Mage::app()->getStore(1)->getWebsiteId());

        /* @var $products Mage_Catalog_Model_Resource_Product_Collection */
        $products =  Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->joinTable('action/products', 'product_id=entity_id', array('aid' => 'action_id'))
            ->addFieldToFilter('aid', $actionId)
            ->addWebsiteFilter($websiteIds)
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

        return $products;
    }

    /**
     * @return Sy_Action_Model_Action|Zend_Controller_Response_Abstract
     * @throws Exception
     */
    public function getActionDetails()
    {
        $actionId = $this->getRequest()->getParam('id');

        /** @var Mage_Core_Model_Date $datemodel */
        $datemodel = Mage::getModel('core/date');
        $curdate = $datemodel->gmtDate('Y-m-d H:i:s');

        $dateHelper = Mage::helper('core');

        /** @var Sy_Action_Model_Action $action */
        $action = Mage::getModel('action/action')
            ->getCollection()
            ->addFieldToFilter('id', $actionId)
            ->addFieldToFilter('end_datetime', array(
                array('gteq' => $curdate),
                array('null' => true)
            ))
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('status', 2)
            ->getFirstItem();

        if ($action->getData()) {

            $action->setResizedImage(Mage::helper('sy_action')->resizeImage($action->getImage(), 300, 300));

            if (!$action->getEndDatetime()) {
                $action->setDuration('from ' . $dateHelper->formatDate($action->getStartDatetime(), 'short', true)
                );
            } else {
                $action->setDuration(
                    'from ' . $dateHelper->formatDate($action->getStartDatetime(), 'short', true) .
                    ' to ' . $dateHelper->formatDate($action->getEndDatetime(), 'short', true)
                );
            }

        } else {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getBaseUrl() . 'actions/index/index');
        }

        return $action;
    }
}
