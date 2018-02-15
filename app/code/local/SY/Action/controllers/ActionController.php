<?php

/**
 * Class Sy_Action_ActionController
 */
class Sy_Action_ActionController extends Mage_Core_Controller_Front_Action
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