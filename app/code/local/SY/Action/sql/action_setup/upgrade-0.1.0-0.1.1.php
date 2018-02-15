<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('action/action'),'status', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable'  => false,
        'after'     => 'is_active',
        'comment'   => 'Action status'
    ));
$installer->endSetup();