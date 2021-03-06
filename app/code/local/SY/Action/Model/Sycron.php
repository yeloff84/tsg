<?php

/**
 * Class Sy_Action_Model_Sycron
 */
class Sy_Action_Model_Sycron
{
    /**
     * @param Mage_Cron_Model_Schedule $schedule
     * @return $this
     */
    public function crontask(Mage_Cron_Model_Schedule $schedule)
    {
        /** @var Mage_Core_Model_Date $datemodel */
        $datemodel = Mage::getModel('core/date');
        $curdate = $datemodel->gmtDate('Y-m-d H:i:s');

        /** @var SY_Action_Model_Action $model */
        $model = Mage::getModel('action/action');
        $actions = $model->getCollection();

        $data = array();

        /** @var SY_Action_Model_Action $action */
        foreach ($actions as $action) {
            if (strtotime($action->getStartDatetime()) > strtotime($curdate)) {
                $model->load($action->getId())->addData(array('status' => 1));
                try {
                    $model->setId($action->getId())->save();
                } catch (Exception $e){
                    Mage::logException($e);
                }
            } elseif ((strtotime($action->getStartDatetime()) < strtotime($curdate)
                && (strtotime($action->getEndDatetime()) > strtotime($curdate) || strtotime($action->getEndDatetime()) == null ))) {
                $model->load($action->getId())->addData(array('status' => 2));
                try {
                    $model->setId($action->getId())->save();
                } catch (Exception $e){
                    Mage::logException($e);
                }
            } else {
                $model->load($action->getId())->addData(array('status' => 3));
                try {
                    $model->setId($action->getId())->save();
                } catch (Exception $e){
                    Mage::logException($e);
                }
            }
        }

        return $this;
    }
}