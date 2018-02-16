<?php

/**
 * Class Sy_Action_Model_Action
 *
 * @method string getId() Returns action primary key
 * @method string getStartDatetime() Returns action start time
 * @method string getEndDatetime() Returns action end time
 * @method string getImage() Returns action image
 * @method string getDescription() Returns action description
 * @method string getShortDescription() Returns action short description
 * @method string getName() Returns action name
 * @method string getResizedImg() Returns resized action image
 *
 */
class Sy_Action_Model_Action extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        $this->_init('action/action');
    }
}
