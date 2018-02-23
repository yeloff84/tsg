<?php

/**
 * Class Tsg_Imgimport_Block_Adminhtml_Form
 */
class Tsg_Imgimport_Block_Adminhtml_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form([
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/import'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ]);

        /** @var  Tsg_Imgimport_Helper_Data $helper */
        $helper = Mage::helper('tsg_imgimport');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array('legend' => $helper->__('CSV Images Import'), 'class' => 'fieldset-wide')
        );

        $fieldset->addField('csv', 'file', [
            'name'      => 'csv',
            'label'     => $helper->__('Choose CSV file'),
            'title'     => $helper->__('Choose CSV file'),
        ]);

        $fieldset->addField('submit', 'submit', [
            'required'  => true,
            'class' => 'form-button',
            'value' => 'Start Import'
        ]);

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
