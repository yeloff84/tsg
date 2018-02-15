<?php

/**
 * Class Sy_Action_Block_Adminhtml_Renderer_Image
 */
class Sy_Action_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());

        if (!empty($value)) {
            return '<img src="' . Mage::helper('sy_action')->resizeImage($value, 50, 50) . '" />';
        }
    }
}