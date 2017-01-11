<?php
$xpdo_meta_map['modTreeItem']= array (
  'package' => 'modtree',
  'version' => '0.0.1',
  'table' => 'modtree_items',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'master' => 0,
    'slave' => 0,
    'linkdate' => NULL,
    'linktitle' => NULL,
    'linktext' => NULL,
    'active' => 1,
  ),
  'fieldMeta' => 
  array (
    'master' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'slave' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'linkdate' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'time',
      'null' => true,
      'index' => 'index',
    ),
    'linktitle' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'index' => 'index',
    ),
    'linktext' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => true,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 1,
    ),
  ),
  'aggregates' => 
  array (
    'ResourceMaster' => 
    array (
      'class' => 'modResource',
      'local' => 'master',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'ResourceSlave' => 
    array (
      'class' => 'modResource',
      'local' => 'slave',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
