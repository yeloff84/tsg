<?php

/**
 * Class Tsg_Imgimport_Block_Adminhtml_Tabs
 */
class Tsg_Imgimport_Block_Adminhtml_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    /**
     * @return Mage_Core_Block_Abstract
     * @throws Exception
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $productId = $this->getRequest()->getParam('id');

        $this->addTab('imports', array(
            'class' => 'ajax',
            'label'     => Mage::helper('catalog')->__('Imported Images'),
            'url' => $this->getUrl('*/imgimport/tab', array('id' => $productId)),
        ));

        return parent::_prepareLayout();
    }
}
