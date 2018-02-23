<?php

/**
 * Class Tsg_Imgimport_Model_Observer
 */
class Tsg_Imgimport_Model_Observer
{
    /**
     * Adding import images button to adminhtml catalog_product
     *
     * @param $observer
     * @return $this
     */
    public function addImportButton($observer)
    {
        $container = $observer->getBlock();

        if (null !== $container && $container->getType() == 'adminhtml/catalog_product') {
            $data = array(
                'label' => 'Import Images',
                'class' => 'go',
                'onclick' => "setLocation('{$container->getUrl('*/imgimport')}')"
            );

            $container->addButton('imgimportbtn', $data);
        }

        return $this;
    }
}
