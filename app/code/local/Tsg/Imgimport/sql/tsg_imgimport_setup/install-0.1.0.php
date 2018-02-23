<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('tsg_imgimport/imgimport');

$installer->getConnection()->dropTable($tableName);

$table = $installer->getConnection()->newTable($tableName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'ID of row')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => 1
    ), '1=in queue 2=uploaded 3=retry 4=error')
    ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Product SKU')
    ->addColumn('url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Image URL')
    ->addColumn('img_size', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Image Size')
    ->addColumn('error', Varien_Db_Ddl_Table::TYPE_VARCHAR, 1000, array(
        'nullable' => true,
    ), 'Upload Error')
    ->addColumn('create_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
        'default' => NOW()
    ), 'Added in queue')
    ->addColumn('upload_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable' => false,
    ), 'Uploaded at');

$installer->getConnection()->createTable($table);

$installer->endSetup();