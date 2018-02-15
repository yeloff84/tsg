<?php

/**
 * Class Sy_Action_Block_Adminhtml_Actions_Edit_Tabs
 */
class Sy_Action_Block_Adminhtml_Actions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Sy_Action_Block_Adminhtml_Actions_Edit_Tabs constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('action_tabs');
        $this->setDestElementId('edit_form');
    }

    /**
     * @return Mage_Core_Block_Abstract
     * @throws Exception
     */
    protected function _beforeToHtml()
    {
        $this->addTab('products', array(
            'label'     => $this->__('Products'),
            'title'     => $this->__('Products'),
            'url'       => $this->getUrl('*/*/productstab', array('_current' => true)),
            'class'     => 'ajax'
        ));
        return parent::_beforeToHtml();
    }

}
