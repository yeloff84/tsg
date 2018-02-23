<?php

/**
 * Class Tsg_Imgimport_Model_Resource_Imgimport
 */
class Tsg_Imgimport_Model_Resource_Imgimport extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('tsg_imgimport/imgimport', 'id');
    }
}
