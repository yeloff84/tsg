<?php

/**
 * Class Sy_Action_Block_Pactions
 */
class Sy_Action_Block_Pactions extends Mage_Core_Block_Template
{

    public function getActionsByProduct()
    {
        $productId = $this->getRequest()->getParam('id');

        $model = Mage::getModel('action/action');
        $pmodel = Mage::getModel('action/products');
        $actions = $model->getCollection()
            ->join(['a'=>'action/products'], 'main_table.id=a.action_id', null, 'left')
            ->addFieldToFilter('a.product_id', $productId);

        return $actions;
    }
}