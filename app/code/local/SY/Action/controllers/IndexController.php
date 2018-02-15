<?php

/**
 * Class Sy_Action_IndexController
 */
class Sy_Action_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     *
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
