<?php

/**
 * Class Sy_Action_Block_Adminhtml_Actions_Edit
 */
class Sy_Action_Block_Adminhtml_Actions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * @var string
     */
    protected $_blockGroup = 'sy_action';

    /**
     * Sy_Action_Block_Adminhtml_Actions_Edit constructor.
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_actions';
        parent::__construct();

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('sy_action')->__('Save and Continue Edit'),
            'onclick'   => "$('save_and_continue').value = 1; editForm.submit();",
            'class'     => 'save',
        ), -100);
    }

    /**
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('*/*/save', array('id' => $this->_getModel()->getId()));
    }

    /**
     * @return mixed
     */
    protected function _getModel()
    {
        $model = Mage::registry('current_action');

        return $model;
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        $helper = Mage::helper('sy_action');
        $model = Mage::registry('current_action');
        if ($model->getId()) {
            return $helper->__("Edit Articles item '%s'", $this->escapeHtml($model->name));
        } else {
            return $helper->__("Add Article item");
        }
    }
}
