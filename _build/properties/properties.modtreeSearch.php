<?php

$properties = array();

$tmp = array(
    'tpl' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.item',
    ),
    'tplSearch' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.itemSearch',
    ),
    'tplSearchField' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.itemSearchField',
    ),
    'tplOuter' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.outerSearch',
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
    'searchLimit' => array(
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