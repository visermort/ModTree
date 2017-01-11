<?php

$properties = array();

$tmp = array(
    'tpl' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.item',
    ),
//    'tplSubitem' => array(
//        'type' => 'textfield',
//        'value' => 'tpl.modTree.subItem',
//    ),
    'tplOuter' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.outer',
    ),
    'tplResource' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.resource',
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