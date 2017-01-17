<?php

$properties = array();

$tmp = array(
    'tplList' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.itemList',
    ),
    'tplTree' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.itemTree',
    ),
    'tplSearchField' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.itemSearchField',
    ),
    'tplOuter' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.outer',
    ),
    'sortBy' => array(
        'type' => 'textfield',
        'value' => 'menuindex',
    ),
    'sortDir' => array(
        'type' => 'list',
        'options' => array(
            array('text' => 'ASC', 'value' => 'ASC'),
            array('text' => 'DESC', 'value' => 'DESC'),
        ),
        'value' => 'ASC',
    ),
    'limit' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
    'limitList' => array(
        'type' => 'numberfield',
        'value' => 15,
    ),
    'searchFields' => array(
        'type' => 'textfield',
        'value' => 'pagetitle,content',
    ),
    'linkWay' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
    'paginateList' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
);

foreach ($tmp as $k => $v) {
    $properties[] = array_merge(
        array(
            'name' => $k,
            'desc' => PKG_NAME_LOWER . '_prop_' . $k,
            'lexicon' => PKG_NAME_LOWER . ':properties',
        ), $v
    );
}

return $properties;