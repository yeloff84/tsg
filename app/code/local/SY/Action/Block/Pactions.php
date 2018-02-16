<?php

/**
 * Class Sy_Action_Block_Pactions
 */
class Sy_Action_Block_Pactions extends Mage_Core_Block_Template
{

    public function getActionsByProduct()
    {
        $productId = $this->getRequest()->getParam('id');

        /** @var Mage_Core_Model_Date $datemodel */
        $datemodel = Mage::getModel('core/date');
        $curdate = $datemodel->gmtDate('Y-m-d H:i:s');

        $model = Mage::getModel('action/action');

        $actions = $model->getCollection()
            ->join(['a'=>'action/products'], 'main_table.id=a.action_id', null, 'left')
            ->addFieldToFilter('a.product_id', $productId)
            ->addFieldToFilter('start_datetime', array('lteq' => $curdate))
            ->addFieldToFilter('end_datetime', array(
                array('gteq' => $curdate),
                array('null' => true)
            ));

        return $actions;
    }
}