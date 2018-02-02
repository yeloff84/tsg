<?php
/**
 * Created by PhpStorm.
 * User: Serzh
 * Date: 01.02.2018
 * Time: 14:50
 */

class SY_Newproducts_Block_List extends Mage_Core_Block_Template
{
    public function getCollection()
    {
        $storeId    = Mage::app()->getStore()->getId();
        //echo $storeId;
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*')
            ->setStoreId($storeId)
            ->addStoreFilter($storeId)
            //->setOrder('ordered_qty', 'desc')
            ->setOrder('created_at', 'desc')
            ->setPageSize(6)
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

        return $collection;
    }
}