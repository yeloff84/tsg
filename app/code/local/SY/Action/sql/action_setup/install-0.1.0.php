<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('action/action');

$installer->getConnection()->dropTable($tableName);

$table = $installer->getConnection()->newTable($tableName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'ID of row')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
    ), 'Action status 0=not active 1=active')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Action name')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_VARCHAR, 1000, array(
        'nullable' => false,
    ), 'Action description')
    ->addColumn('short_description', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Action short description')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Action image url')
    ->addColumn('create_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
        'default' => NOW()
    ), 'Action created at')
    ->addColumn('start_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
    ), 'Action started at')
    ->addColumn('end_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => true,
    ), 'Action end at');

$installer->getConnection()->createTable($table);

$installer->endSetup();