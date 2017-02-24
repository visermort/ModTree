<?php

$properties = [];

$tmp = [];

foreach ($tmp as $k => $v) {
    $properties[] = array_merge(
        [   'name' => $k,
            'desc' => PKG_NAME_LOWER . '_prop_' . $k,
            'lexicon' => PKG_NAME_LOWER . ':properties',],
        $v
    );
}

return $properties;