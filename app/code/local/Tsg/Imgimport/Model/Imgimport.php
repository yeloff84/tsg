<?php


/**
 * Class Tsg_Imgimport_Model_Imgimport
 */

class Tsg_Imgimport_Model_Imgimport extends Mage_Core_Model_Abstract
{
    const INQUEUE = 1;
    const UPLOADED = 2;
    const RETRY = 3;
    const ERROR = 4;

    public function _construct()
    {
        $this->_init('tsg_imgimport/imgimport');
    }
}
