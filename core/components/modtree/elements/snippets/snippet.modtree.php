<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var modTree $modTree */

//шаблон списка
$tplList = $modx->getOption('tplList', $scriptProperties, 'tpl.ModTree.itemList');
//шаблон для элемента дерева
$tplTree = $modx->getOption('tplTree', $scriptProperties, 'ModTree.itemTree');
//шаблон поля полей поиска
$tplSearchField = $modx->getOption('tplSearchField', $scriptProperties, 'tpl.ModTree.itemSearchField');
//шаблон вывода
$tplOuter = $modx->getOption('tplOuter', $scriptProperties, 'tpl.ModTree.outer');
//шаблон кнопок пагинации
$tplButtons = $modx->getOption('tplButtons', $scriptProperties, 'tpl.ModTree.paginateBtns');

//сортировка
$sortBy = $modx->getOption('sortBy', $scriptProperties, 'menuindex');
$sortDir = $modx->getOption('sortDir', $scriptProperties, 'ASC');
//ограничиние в дереве
$limit = $modx->getOption('limit', $scriptProperties, 0);
//ограничение в списке
$limitList = $modx->getOption('limitList', $scriptProperties, 15);
//поиск для списка: 1 - связей, 0 - ресурсов
$queryLinks = $modx->getOption('queryLinks', $scriptProperties, '1');
//родительский для списка, по умолчанию текуций ресурс
$parent = $modx->getOption('parent', $scriptProperties, $modx->resource->get('id'));
//делать ли поиск сразу  - если поиск связей, то 1
//делать ли поиск сразу  - если поиск связей, то 1
$queryForce = $modx->getOption('queryForce', $scriptProperties, '1');
//направление связи: 0 - в обе стороны, 1 - master->slave, -1 - slave->master
$linkWay = $modx->getOption('linkWay', $scriptProperties, 0);
//вид кнопок пагинации - пока только список
$paginateList = $modx->getOption('paginateList', $scriptProperties, 0);
//префикс id полей контентк
$contentIdPrefix = $modx->getOption('contentIdPrefix', $scriptProperties, 'modtree-');
//поля поиска
$searchFields = explode(',', $modx->getOption('searchFields', $scriptProperties, 'padetitle, content'));
//подключать ли встроенный CSS
$customCss = $modx->getOption('customCss', $scriptProperties, 0);

//подключаем css
if (!$customCss) {
    $modx->regClientCSS('/assets/components/modtree/css/web/modtree.css');
}
//подключаем скрипт
$modx->regClientScript('/assets/components/modtree/js/web/modtree.js');

//если ищем связи, то поиск сразу, поля для поиска очищаем
//if ($queryLinks == 1) {
   // $parent = $parent ? $parent : $modx->resource->get('id');
   // $queryForce = 1;
   // $searchFields = [];
//}

$items = '';
$buttons = '';
$resMaster = [];
$itemsSearch = '';
$pagination = [];

//поля для поиска

//$modx->lexicon->load($modx->getOption('cultureKey').':resource');
$modx->lexicon->load($modx->getOption('cultureKey').':default');

foreach ($searchFields as $searchField) {
    $itemsSearch .= $modx->getChunk($tplSearchField, [
        'name' => $searchField,
  //      'label' => $modx->lexicon('resource_'.$searchField),//$searchField,
        'label' => $modx->lexicon($searchField),//$searchField,
    ]);
}

if ($queryForce == 1) {
    //если поиск "сразу"
    //run processor

    $result = $modx->runProcessor('web/resource/getlist', [
        'id' => $parent,
        'sortBy' => $sortBy,
        'sortDir' => $sortDir,
        'limit' => $limitList,
        'paginateList' => $paginateList,
        'queryLinks' => $queryLinks,
    ], [
        'processors_path' => $modx->getOption('modtree_core_path') . 'processors/',
    ]);

    $resMaster = $result->response['object']['items'];
    $pagination = $result->response['object']['pagination'];
    //search result - на будущее, если нужен изначальный список
    foreach ($resMaster as $item) {
        $items .= $modx->getChunk($tplList, $item);
    }
    foreach ($result->response['object']['pagination']['buttons'] as $button) {
        $buttons .= $modx->getChunk($tplButtons, [
            'page' => $button['page'],
            'current' => $button['current'],
        ]);
    }
}


//templates
$itemHiddenList = $modx->getChunk($tplList, []);
$itemHiddenTree = $modx->getChunk($tplTree, []);
$buttonHidden = $modx->getChunk($tplButtons, []);
//Output
return $modx->getChunk($tplOuter, [
    'id' => $parent,
    'itemHiddenList' => $itemHiddenList,
    'itemHiddenTree' => $itemHiddenTree,
    'buttonHidden' => $buttonHidden,
    'searchfields' => $itemsSearch,
    'items' => $items,
    'sortBy' => $sortBy,
    'sortDir' => $sortDir,
    'limit' => $limit,
    'limitList' => $limitList,
    'pagination' => $pagination,
    'paginateList' => $paginateList,
    'linkWay' => $linkWay,
    'contentIdPrefix' => $contentIdPrefix,
    'buttons' => $buttons,
    'queryLinks' => $queryLinks,
]);