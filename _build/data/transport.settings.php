<?php
/** @var modX $modx */
/** @var array $sources */

$settings = array();

$tmp = array(
    'core_path' => array(
        'xtype' => 'textfield',
        'value' => '{core_path}components/modtree/',
        'area' => 'modtree_main',
    ),
    'assets_path' => array(
        'xtype' => 'textfield',
        'value' => '{assets_path}components/modtree/',
        'area' => 'modtree_main',
    ),
    'assets_urs' => array(
        'xtype' => 'textfield',
        'value' => '/assets/components/modtree/',
        'area' => 'modtree_main',
    ),
    'date_format' => array(
        'xtype' => 'textfield',
        'value' => 'd.m.Y',
        'area' => 'modtree_main',
    ),
    'time_format' => array(
        'xtype' => 'textfield',
        'value' => 'H:i',
        'area' => 'modtree_main',
    ),
    /*
    'some_setting' => array(
        'xtype' => 'combo-boolean',
        'value' => true,
        'area' => 'modtree_main',
    ),
    */
);

foreach ($tmp as $k => $v) {
    /** @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => 'modtree_' . $k,
            'namespace' => PKG_NAME_LOWER,
        ), $v
    ), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;
