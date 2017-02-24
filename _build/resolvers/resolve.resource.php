<?php

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $resource = $modx->getObject('modResource', ['pagetitle' => 'modtree_resource']);
        if ($resource == null) {
                $resource = $modx->newObject('modResource');
                $modx->log(modX::LOG_LEVEL_INFO, 'Create new resource modtreeajax.php');
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
        $modx->log(modX::LOG_LEVEL_INFO, 'Save modtreeajax.php');
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        $query = $modx->newQuery('modResource');
        $query->command('delete');
        $query->where(['padetitle' => 'modtree_resource']);
        $query->prepare();
        $query->stmt->execute();
        $modx->log(modX::LOG_LEVEL_INFO, 'Delete resource modtreeajax.php');

        break;
}


