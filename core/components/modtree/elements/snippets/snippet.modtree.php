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
//$tplSubitem = $modx->getOption('tplSubitem', $scriptProperties, 'tplSubItem');
$tplOuter = $modx->getOption('tplOuter', $scriptProperties, 'tplOuter');
$tplResource = $modx->getOption('tplResource', $scriptProperties, 'tplResource');

$sortBy = $modx->getOption('sortBy', $scriptProperties, 'pagetitle');
$sortDir = $modx->getOption('sortDir', $scriptProperties, 'ASC');
$limit = $modx->getOption('limit', $scriptProperties, 0);
$linkWay = $modx->getOption('linkWay', $scriptProperties, 0);
$parent = $modx->getOption('parent', $scriptProperties, $modx->resource->get('id'));


//run processor

$result = $modx->runProcessor('web/tree/getlist', [
        'id' => $parent,
        'sortBy' => $sortBy,
        'sortDir' => $sortDir,
        'limit' => $limit,
        'linkWay' => $linkWay,
    ],[
        'processors_path' => $modx->getOption('modtree_core_path').'processors/',
    ]);

$resMaster = $result->response['object'];
//return print_r($resMaster['object']);

//Output
$items = '';
foreach ($resMaster as $item) {
    $items .= $modx->getChunk($tpl, $item);
}

return $modx->getChunk($tplOuter, [
    'items' => $items,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'limit' => $limit,
    'linkWay' => $linkWay,
    'connector' => $modx->getOption('modtree_assets_urs').'connector.php',
]);
