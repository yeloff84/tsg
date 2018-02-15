<?php

/**
 * Class Sy_Action_Model_Resource_Products
 */
class Sy_Action_Model_Resource_Products extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     *
     */
    public function _construct()
    {
        $this->_init('action/products', 'id');
    }
}