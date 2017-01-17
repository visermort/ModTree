<?php

$action = $_REQUEST['action'];
if (!in_array($action, ['web/tree/getlist', 'web/resource/get', 'web/resource/getlist'])){

    $url404 = $modx->makeUrl($modx->getOption('error_page'));
    $modx->sendRedirect($url404);
}

$path = $modx->getOption('modtree_core_path'). 'processors/';
// exit(json_encode([
//     'request' => $_REQUEST,
//     '$_SERVER[HTTP_X_REQUESTED_WITH]' => $_SERVER['HTTP_X_REQUESTED_WITH'],
//     'path' => $path,
// ]));
$result = $modx->runProcessor($action, $_POST, [
    'processors_path' => $path,
]);
if (!$result->isError()) {
    exit(json_encode($result->response));
} else {
    exit(json_encode(['error' => $result->getMessage()]));
}