<?php

$properties = array();

$tmp = array(
    'tplList' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.itemTree',
    ),
    'tplTree' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.itemTree',
    ),
    'tplOuter' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.outer',
    ),
    'tplResource' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.resource',
    ),
    'tplButtons' => array(
        'type' => 'textfield',
        'value' => 'tpl.modTree.paginateBtns',
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
    'linkWay' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
    'contentIdPrefix' => [
        'type' => 'textfield',
        'value' => 'modtree-',
    ],
    
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