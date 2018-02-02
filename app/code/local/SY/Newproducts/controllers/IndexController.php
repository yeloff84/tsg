<?php

class SY_Newproducts_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();

        //var_dump($this->getLayout()->getUpdate()->getHandles());
        //$block = $this->getLayout()->createBlock('core/template')->setTemplate('aaa/index.phtml');
        //$html = $block->toHtml();

        $this->renderLayout();

    }
}
