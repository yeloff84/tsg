<?php

/**
 * Class Tsg_Imgimport_Block_Adminhtml_Renderer_Imgimport
 */
class Tsg_Imgimport_Block_Adminhtml_Renderer_Imgimport extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $str = $row->getData('img_size');

        /** @var Tsg_Imgimport_Helper_Data $helper */
        $helper = Mage::helper('tsg_imgimport');

        $prepStr = $helper->humanFileSize($str);

        return $prepStr;
    }
}
