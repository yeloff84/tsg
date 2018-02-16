<?php

/**
 * Class Sy_Action_Block_List
 */
class Sy_Action_Block_List extends Mage_Core_Block_Template
{
    /**
     * @return mixed
     */
    public function getActionsList() {

        /** @var Mage_Core_Model_Date $datemodel */
        $datemodel = Mage::getModel('core/date');
        $curdate = $datemodel->gmtDate('Y-m-d H:i:s');
        $dateHelper = Mage::helper('core');

        $collection = Mage::getModel('action/action')->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('start_datetime', array('lteq' => $curdate))
            ->addFieldToFilter('end_datetime', array(
                array('gteq' => $curdate),
                array('null' => true)
            ))
            ->addFieldToFilter('is_active', 1)
            ->setOrder('start_datetime','DESC');

        foreach ($collection as $col) {

            $col->setResizedImage(Mage::helper('sy_action')->resizeImage($col->getImage(), 200, 200));

            if (!$col->getEndDatetime()) {
                $col->setDuration('never ending');
            } else {
                $col->setDuration(
                    'untill ' . $dateHelper->formatDate($col->getEndDatetime(), 'short', false)
                );
            }
        }

        return $collection;
    }
}
