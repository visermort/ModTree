<?php
ini_set('display_errors', 1);
ini_set('error_reporting', -1);

if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var modTree $modTree */
$modTree = $modx->getService('modtree', 'modTree', $modx->getOption('modtree_core_path', null,
        $modx->getOption('core_path') . 'components/modtree/') . 'model/modtree/'
);
$modx->lexicon->load('modtree:default');

// handle request
$corePath = $modx->getOption('modtree_core_path', null, $modx->getOption('core_path') . 'components/modtree/');
$path = $modx->getOption('processorsPath', $modTree->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;

$action = $_SERVER['HTTP_ACTION'];
if (in_array($action, ['web/tree/getlist', 'web/resource/get', 'web/resource/getlist'])) {
    $result = $modx->runProcessor($action, $_POST, [
        'processors_path' => $path,
    ]);
    //exit(json_encode($result->response));
    if (!$result->isError()) {
        exit(json_encode($result->response));
    } else {
        exit(json_encode(['error' => $result->getMessage() ]));
    }
}
//if ($action == 'web/resource/get') {
////    $response = $modx->runProcessor('resource/get', $_POST);
//    $result = $modx->runProcessor($action, $_POST, [
//        'processors_path' => $path,
//    ]);
//    // exit(json_encode($result->response));
//    if (!$result->isError()) {
//        exit(json_encode($result->response));
//    } else {
//        exit(json_encode(['error' => $result->getMessage() ]));
//    }
//}
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));