<?php

/**
 * Class Sy_Action_Block_Adminhtml_Actions
 */
class Sy_Action_Block_Adminhtml_Actions extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $helper = Mage::helper('sy_action');
        $this->_blockGroup = 'sy_action';
        $this->_controller = 'adminhtml_actions';
        $this->_headerText = $helper->__('Action Management');
        $this->_addButtonLabel = $helper->__('Add Action');
    }
}
