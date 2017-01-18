<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var modTree $modTree */
//if (!$modTree = $modx->getService('modtree', 'modTree', $modx->getOption('modtree_core_path', null,
//        $modx->getOption('core_path') . 'components/modtree/') . 'model/modtree/', $scriptProperties)
//) {
//    return 'Could not load modTree class!';
//}

$tplList = $modx->getOption('tplList', $scriptProperties, 'tpl.ModTree.itemList');
$tplTree = $modx->getOption('tplTree', $scriptProperties, 'ModTree.itemTree');
$tplSearchField = $modx->getOption('tplSearchField', $scriptProperties, 'tpl.ModTree.itemSearchField');
$tplOuter = $modx->getOption('tplOuter', $scriptProperties, 'tpl.ModTree.outer');
$tplButtons = $modx->getOption('tplButtons', $scriptProperties, 'tpl.ModTree.paginateBtns');

$sortBy = $modx->getOption('sortBy', $scriptProperties, 'menuindex');
$sortDir = $modx->getOption('sortDir', $scriptProperties, 'ASC');
$limit = $modx->getOption('limit', $scriptProperties, 0);
$limitList = $modx->getOption('limitList', $scriptProperties, 15);
$parent = $modx->getOption('parent', $scriptProperties, '');
$linkWay = $modx->getOption('linkWay', $scriptProperties, 0);
$paginateList = $modx->getOption('paginateList', $scriptProperties, 0);
$contentIdPrefix = $modx->getOption('contentIdPrefix', $scriptProperties, 'modtree-');

$searchFields = explode(',', $modx->getOption('searchFields', $scriptProperties, 'padetitle, content'));


//run processor

$result = $modx->runProcessor('web/resource/getlist', [
        'id' => $parent,
        'sortBy' => $sortBy,
        'sortDir' => $sortDir,
        'limit' => $limitList,
        'paginateList' => $paginateList,
        'queryLink' => 0,
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
//search result - на будущее, если нужен изначальный список
$items = '';
foreach ($resMaster as $item) {
    $items .= $modx->getChunk($tplList, $item);
}
$buttons = '';
foreach ($result->response['object']['pagination']['buttons'] as $button) {
    $buttons .= $modx->getChunk($tplButtons, [
        'page' => $button['page'],
        'current' => $button['current'],
    ]);
}

$itemHiddenList = $modx->getChunk($tplList, []);
$itemHiddenTree = $modx->getChunk($tplTree, []);
$buttonHidden = $modx->getChunk($tplButtons, []);

return $modx->getChunk($tplOuter, [
    'itemHiddenList' => $itemHiddenList,
    'itemHiddenTree' => $itemHiddenTree,
    'buttonHidden' => $buttonHidden,
    'searchfields' => $itemsSearch,
    'items' => $items,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'limit' => $limit,
    'limitList' => $limitList,
    'pagination' => $result->response['object']['pagination'],
    'paginateList' => $paginateList,
    'linkWay' => $linkWay,
    'button-label' => 'Поиск',
    'contentIdPrefix' => $contentIdPrefix,
    'buttons' => $buttons,
]);
