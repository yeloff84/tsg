<?php

/**
 * Class Sy_Action_Model_Resource_Action
 */
class Sy_Action_Model_Resource_Action extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     *
     */
    public function _construct()
    {
        $this->_init('action/action', 'id');
    }
}