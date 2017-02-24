<?php

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $resource = $modx->getObject('modResource', ['pagetitle' => 'modtree_resource']);
        if ($resource == null) {
                $resource = $modx->newObject('modResource');
                $modx->log(modX::LOG_LEVEL_INFO, 'Создаём новый ресурс modtreeajax.php');
        }
        $resource->set('alias', 'modtreeajax');
        $resource->set('uri', 'modtreeajax.php');
        $resource->set('uri_override', 1);
        $resource->set('published', 1);
        $resource->set('isfolder', 0);
        $resource->set('pagetitle', 'modtree_resource');
        $resource->set('hidemenu', 1);
        $resource->set('template', 0);
        $resource->set('show_in_tree', 0);
        $resource->setContent('[[!modTreeAjax]]');
        $resource->save();
        $modx->log(modX::LOG_LEVEL_INFO, 'Сохранение modtreeajax.php');
        break;

    case xPDOTransport::ACTION_UNINSTALL:
//        $resource = $modx->getObject('modResource', ['pagetitle' => 'modtree_resource']);
//        if ($resource != null) {
//            $resource->delete();
//            $modx->log(modX::LOG_LEVEL_INFO, 'Удалён ресурс modtreeajax.php');
//        }
        break;
}


