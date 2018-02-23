<?php

/**
 * Class Tsg_Imgimport_Model_Resource_Imgimport_Collection
 */
class Tsg_Imgimport_Model_Resource_Imgimport_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('tsg_imgimport/imgimport');
    }
}
