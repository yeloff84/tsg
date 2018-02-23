<?php

/**
 * Class Tsg_Imgimport_Block_Adminhtml_Tabs_Imgimport
 */
class Tsg_Imgimport_Block_Adminhtml_Tabs_Imgimport extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Tsg_Imgimport_Block_Adminhtml_Tabs_Imgimport constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('imports');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/importsgrid', array('_current' => true));
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Images Imports');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Images Imports');
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

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     * @throws Exception
     */
    public function _prepareCollection() {

        $productId = $this->getRequest()->getParam('id');

        /** @var Tsg_Imgimport_Model_Imgimport $model */
        $model = Mage::getModel('tsg_imgimport/imgimport');

        $collection = $model->getCollection()
            ->join(['p' => 'catalog/product'], 'main_table.sku=p.sku', null, 'left')
            ->addFieldToFilter('p.entity_id', $productId);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns() {

        $helper = Mage::helper('tsg_imgimport');
        $dateFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $this->addColumn('imports_id', array(
            'header'    => $helper->__('ID'),
            'align'     => 'left',
            'index'     => 'id',
            'width'     => '10'
        ));

        $this->addColumn('imports_created', array(
            'header'    => $helper->__('Created'),
            'align'     => 'left',
            'index'     => 'create_datetime',
            'width'     => '10',
            'type'      => 'datetime',
            'format'    => $dateFormat
        ));

        $this->addColumn('imports_uploaded', array(
            'header'    => $helper->__('Uploaded'),
            'align'     => 'left',
            'index'     => 'upload_datetime',
            'width'     => '10',
            'type'      => 'datetime',
            'format'    => $dateFormat,
        ));

        $this->addColumn('imports_url', array(
            'header'    => $helper->__('Url'),
            'align'     => 'left',
            'index'     => 'url',
            'width'     => '250'
        ));

        $this->addColumn('imports_size', array(
            'header'    => $helper->__('Image size'),
            'align'     => 'left',
            'index'     => 'img_size',
            'width'     => '100',
            'renderer' => 'Tsg_Imgimport_Block_Adminhtml_Renderer_Imgimport'
        ));

        $this->addColumn('imports_status', array(
            'header'    => $helper->__('Status'),
            'align'     => 'left',
            'index'     => 'status',
            'width'     => '10'
        ));

        return parent::_prepareColumns();
    }
}
