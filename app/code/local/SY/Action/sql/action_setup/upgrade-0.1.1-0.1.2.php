<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('action/products');

$installer->getConnection()->dropTable($tableName);

$table = $installer->getConnection()->newTable($tableName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'ID of row')
    ->addColumn('action_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'Action id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'Action product id');

$table->addIndex(
    'actions_products',
    array('action_id', 'product_id'),
    array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
);

$installer->getConnection()->createTable($table);

$installer->endSetup();