<?php

/**
 * Class Sy_Action_Block_Adminhtml_Actions_Grid
 */
class Sy_Action_Block_Adminhtml_Actions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection  = Mage::getModel("action/action")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $helper = Mage::helper('sy_action');
        $this->addColumn('id', array(
            'header' => $helper->__('Action ID'),
            'width' => '30px',
            'index' => 'id',
            'type' => 'number'
        ));
        $this->addColumn('name', array(
            'header' => $helper->__('Action Title'),
            'width' => '200px',
            'index' => 'name',
            'type' => 'text',
        ));
        $this->addColumn('image', array(
            'header' => $helper->__('Action Image'),
            'width' => '50px',
            'index' => 'image',
            'renderer' => 'SY_Action_Block_Adminhtml_Renderer_Image',
        ));
        $this->addColumn('is_active', array(
            'header' => $helper->__('Active'),
            'width' => '50px',
            'index' => 'is_active',
            'type'=>'options',
            'options' => array('1' => 'Yes', '0' => 'No')
        ));
        $this->addColumn('start_datetime', array(
            'header' => $helper->__('Action Starts'),
            'width' => '50px',
            'index' => 'start_datetime',
            'type'=>'date',
        ));
        $this->addColumn('end_datetime', array(
            'header' => $helper->__('Action Ends'),
            'width' => '50px',
            'index' => 'end_datetime',
            'type'=>'date',
        ));
        return parent::_prepareColumns();
    }

    /**
     * @param $model
     * @return string
     */
    public function getRowUrl($model)
    {
        return $this->getUrl('*/*/edit', array(
            'id' => $model->getId(),
        ));
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('action');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
        ));

        return $this;
    }
}