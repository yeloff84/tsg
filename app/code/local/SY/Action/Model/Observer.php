<?php

/**
 * Class Sy_Action_Model_Observer
 */
class Sy_Action_Model_Observer {

    /**
     * @param Varien_Event_Observer $observer
     */
    public function addToTopmenuItem(Varien_Event_Observer $observer) {

        $menu = $observer->getMenu();
        $tree = $menu->getTree();

        $data = array(
            'id' => 'node-sy-1',
            'name' => 'Actions',
            'url' => '/actions/index/index'
        );

        $node = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
        $menu->addChild($node);
    }
}
