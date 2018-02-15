<?php

/**
 * Class Sy_Action_Block_Adminhtml_Actions_Edit_Tab_Products
 */
class Sy_Action_Block_Adminhtml_Actions_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Sy_Action_Block_Adminhtml_Actions_Edit_Tab_Products constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('products');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);
        if ($this->getCurrentAction() && $this->getCurrentAction()->getId()) {
            $this->setDefaultFilter(array('in_products' => 1));
        }
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsgrid', array('_current' => true));
    }

    /**
     * @return mixed
     */
    protected function getCurrentAction()
    {
        return Mage::registry('current_action');
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Products');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Products');
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
     * @return array
     */
    public function getSelectedProducts()
    {
        $actionId = $this->getCurrentAction()->id;

        $collection = Mage::getModel('action/products')->getCollection()
            ->addFieldToFilter('action_id', $actionId);

        $productsIds = [];

        foreach ($collection as $item) {
            $productsIds[] = $item->product_id;
        }

        return $productsIds;
    }

    /**
     * @param $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_products') {
            $productsIds = $this->getSelectedProducts();
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productsIds));
            } else {
                if($productsIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productsIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    public function _prepareCollection() {

        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns() {

        $this->addColumn('in_products', array(
            'header_css_class'  => 'a-center',
            'type'              => 'checkbox',
            'name'              => 'in_products',
            'values'            => $this->getSelectedProducts(),
            'align'             => 'center',
            'index'             => 'entity_id'
        ));

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('sy_action')->__('ID'),
            'align'     => 'left',
            'index'     => 'entity_id',
            'width'     => '10'
        ));

        $this->addColumn('pname', array(
            'header'    => Mage::helper('sy_action')->__('Name'),
            'align'     => 'left',
            'index'     => 'name',
            'width'     => '250'
        ));

        $this->addColumn('ptype', array(
            'header'    => Mage::helper('sy_action')->__('Type'),
            'align'     => 'left',
            'index'     => 'type',
            'width'     => '10'
        ));

        $this->addColumn('pvisibility', array(
            'header'    => Mage::helper('sy_action')->__('Visibility'),
            'align'     => 'left',
            'index'     => 'visibility',
            'width'     => '10'
        ));

        $this->addColumn('psku', array(
            'header'    => Mage::helper('sy_action')->__('SKU'),
            'align'     => 'left',
            'index'     => 'sku',
            'width'     => '50'
        ));

        return parent::_prepareColumns();
    }
}
