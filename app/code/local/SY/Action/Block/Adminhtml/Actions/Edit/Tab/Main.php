<?php

/**
 * Class Sy_Action_Block_Adminhtml_Actions_Edit_Tab_Main
 */
class Sy_Action_Block_Adminhtml_Actions_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('current_action');
        $mdata = $model->getData();

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array('legend' => Mage::helper('cms')->__('General Information'), 'class' => 'fieldset-wide')
        );

        $fieldset->addField('save_and_continue', 'hidden', array(
            'name' => 'save_and_continue'
        ));

        if ($model->getId()) {
            $fieldset->addField('id', 'label', array(
                'name' => 'id',
                'label'     => Mage::helper('sy_action')->__('Id'),
                'title'     => Mage::helper('sy_action')->__('Id'),
            ));
        }


        /** @var  SY_Action_Helper_Data $helper */
        $helper = Mage::helper('sy_action');
        $imgPreview = $model->getImage() != '' ? $helper->resizeImage($model->getImage(), 22, 22, false) : '';

        $fieldset->addField('image', 'image', array(
            'name'      => 'image',
            'label'     => Mage::helper('sy_action')->__('Action Image'),
            'title'     => Mage::helper('sy_action')->__('Action Image'),
        ));

        $dateFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('start_datetime', 'date', array(
            'name'      => 'start_datetime',
            'label'     => Mage::helper('sy_action')->__('Start datetime'),
            'title'     => Mage::helper('sy_action')->__('Start datetime'),
            'input_format' => $dateFormat,
            'format'    => $dateFormat,
            'time'      => true,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'required'  => true,
        ));

        $fieldset->addField('end_datetime', 'date', array(
            'name'      => 'end_datetime',
            'label'     => Mage::helper('sy_action')->__('End datetime'),
            'title'     => Mage::helper('sy_action')->__('End datetime'),
            'input_format' => $dateFormat,
            'format'    => $dateFormat,
            'time'      => true,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'required'  => false,
        ));

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('sy_action')->__('Action Name'),
            'title'     => Mage::helper('sy_action')->__('Action Name'),
            'required'  => true,
        ));

        $fieldset->addField('description', 'textarea', array(
            'name'      => 'description',
            'label'     => Mage::helper('sy_action')->__('Action Description'),
            'title'     => Mage::helper('sy_action')->__('Action Description'),
            'required'  => false,
        ));

        $fieldset->addField('short_description', 'textarea', array(
            'name'      => 'short_description',
            'label'     => Mage::helper('sy_action')->__('Action Short Description'),
            'title'     => Mage::helper('sy_action')->__('Action Short Description'),
            'required'  => false,
        ));

        $fieldset->addField('is_active', 'checkbox', array(
            'name'      => 'is_active',
            'label'     => Mage::helper('sy_action')->__('Is active'),
            'title'     => Mage::helper('sy_action')->__('Is active'),
            'required'  => false,
            'onclick'   => "$('is_active').value = this.checked ? 1 : 0",
        ))->setChecked($mdata['is_active']);

        $fieldset->addField('status', 'text', array(
            'name'      => 'status',
            'readonly'  => true,
            'label'     => Mage::helper('sy_action')->__('Status'),
            'title'     => Mage::helper('sy_action')->__('Status'),
        ));

        $mdata['image'] = $imgPreview;

        $form->setValues($mdata);
        $form->setUseContainer(false);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Main');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Main');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
