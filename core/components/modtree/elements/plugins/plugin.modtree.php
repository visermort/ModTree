<?php
/** @var modX $modx */
//при удалении ресурса, удалить его связи
if ($modx->event->name = 'OnDocFormDelete') {
    //$id передаётся
    $query = $modx->newQuery('modTreeItem');
    $query->command('delete');
    $query->where(['master' => $id]);
    $query->orCondition(['slave' => $id]);

    $query->prepare();

    $query->stmt->execute();

}