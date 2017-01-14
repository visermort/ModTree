<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var modTree $modTree */
//if (!$modTree = $modx->getService('modtree', 'modTree', $modx->getOption('modtree_core_path', null,
//        $modx->getOption('core_path') . 'components/modtree/') . 'model/modtree/', $scriptProperties)
//) {
//    return 'Could not load modTree class!';
//}

$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl');
$tplSearch = $modx->getOption('tplSearch', $scriptProperties, 'tplItemSearch');
$tplSearchField = $modx->getOption('tplSearchField', $scriptProperties, 'tplItemSearchField');
$tplOuter = $modx->getOption('tplOuter', $scriptProperties, 'tplOuterSearch');

$sortBy = $modx->getOption('sortBy', $scriptProperties, 'menuindex');
$sortDir = $modx->getOption('sortDir', $scriptProperties, 'ASC');
$limit = $modx->getOption('limit', $scriptProperties, 0);
$searchLimit = $modx->getOption('searchLimit', $scriptProperties, 15);
$parent = $modx->getOption('parent', $scriptProperties, '');
$linkWay = $modx->getOption('parent', $scriptProperties, 0);

$searchFields = explode(',', $modx->getOption('searchFields', $scriptProperties, 'padetitle, content'));


//run processor

$result = $modx->runProcessor('web/resource/getlist', [
        'parent' => $parent,
        'sortBy' => $sortBy,
        'sortDir' => $sortDir,
        'limit' => $limit,
    ],[
        'processors_path' => $modx->getOption('modtree_core_path').'processors/',
    ]);

$resMaster = $result->response['object']['items'];
//return print_r($resMaster['object']);

//Output
//search fields
$itemsSearch = '';
//return print_r($searchFields, 1);
foreach ($searchFields as $searchField) {
    $itemsSearch .= $modx->getChunk($tplSearchField, [
        'name' => $searchField,
        'label' => $searchField,
    ]);

}
//search result
$items = '';
foreach ($resMaster as $item) {
    $items .= $modx->getChunk($tplSearch, $item);
}

$itemHidden = $modx->getChunk($tpl, []);

return $modx->getChunk($tplOuter, [
    'itemhidden' => $itemHidden,
    'searchfields' => $itemsSearch,
    'items' => $items,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'limit' => $limit,
    'searchLimit' => $searchLimit,
    'linkWay' => $linkWay,
    'button-label' => 'Поиск',
    'connector' => $modx->getOption('modtree_assets_urs').'connector.php',
]);
