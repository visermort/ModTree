<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var modTree $modTree */
//if (!$modTree = $modx->getService('modtree', 'modTree', $modx->getOption('modtree_core_path', null,
//        $modx->getOption('core_path') . 'components/modtree/') . 'model/modtree/', $scriptProperties)
//) {
//    return 'Could not load modTree class!';
//}

$tplList = $modx->getOption('tplList', $scriptProperties, 'tpl.ModTree.itemTree');
$tplTree = $modx->getOption('tplTree', $scriptProperties, 'ModTree.itemTree');
$tplOuter = $modx->getOption('tplOuter', $scriptProperties, 'tpl.ModTree.outer');
$tplButtons = $modx->getOption('tplButtons', $scriptProperties, 'tpl.ModTree.paginateBtns');

$sortBy = $modx->getOption('sortBy', $scriptProperties, 'pagetitle');
$sortDir = $modx->getOption('sortDir', $scriptProperties, 'ASC');
$limit = $modx->getOption('limit', $scriptProperties, 0);
$limitList = $modx->getOption('limitList', $scriptProperties, 15);
$linkWay = $modx->getOption('linkWay', $scriptProperties, 0);
$parent = $modx->getOption('parent', $scriptProperties, $modx->resource->get('id'));
$paginateList = $modx->getOption('paginateList', $scriptProperties, 0);
$contentIdPrefix = $modx->getOption('contentIdPrefix', $scriptProperties, 'modtree-');


//run processor

$result = $modx->runProcessor('web/resource/getlist', [
        'id' => $parent,
        'sortBy' => $sortBy,
        'sortDir' => $sortDir,
        'limit' => $limitList,
        'linkWay' => $linkWay,
        'paginateList' => $paginateList,
        'queryLink' => 1,
    ],[
        'processors_path' => $modx->getOption('modtree_core_path').'processors/',
    ]);

$resMaster = $result->response['object']['items'];
//return print_r($resMaster['object']);

//Output
$items = '';
foreach ($resMaster as $item) {
    $items .= $modx->getChunk($tplList, $item);
}
$itemHiddenList = $modx->getChunk($tplList, []);
$itemHiddenTree = $modx->getChunk($tplTree, []);
$buttonHidden = $modx->getChunk($tplButtons, []);


return $modx->getChunk($tplOuter, [
    'itemHiddenList' => $itemHiddenList,
    'itemHiddenTree' => $itemHiddenTree,
    'buttonHidden' => $buttonHidden,
    'items' => $items,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'limit' => $limit,
    'limitList' => $limitList,
    'pagination' => $result->response['object']['pagination'],
    'paginateList' => $paginateList,
    'linkWay' => $linkWay,
    'contentIdPrefix' => $contentIdPrefix,
    'buttons' => $buttons,
]);
