<?php

class modTreeTreeGetListProcessor extends modProcessor
{

    public function checkPermissions()
    {
        return true;
    }


    public function process()
    {
        $id = $this->getProperty('id');
        $limit = $this->getProperty('limit');
        $sortBy = $this->getProperty('sortBy');
        $sortDir = $this->getProperty('sortDir');
        $linkWay = $this->getProperty('linkWay');

        // Build query
        /*select mr.*,mt.linkdate,mt.linktitle,mt.linktext from `modx_site_content` mr inner join `modx_modtree_items` mt on mr.`id` = mt.`slave`
         where `master` = 1 union select mr.*,mt.linkdate,mt.linktitle,mt.linktext from `modx_site_content` mr inner join `modx_modtree_items` mt
        on mr.`id` = mt.`master`  where `slave` = 1 */

        $resources = $this->modx->getTableName('modResource');
        $treeItems = $this->modx->getTableName('modTreeItem');
        if ($linkWay > 0) {
            $sql = 'SELECT mr.*,mt.linkdate,mt.linktitle,mt.linktext FROM ' . $resources . ' mr INNER JOIN ' .
                $treeItems . ' mt on mr.`id` = mt.`slave`' . ' where `master` = :id ';
        } elseif ($linkWay < 0 ) {
            $sql = 'select mr.*,mt.linkdate,mt.linktitle,mt.linktext from ' . $resources . ' mr inner join ' .
                $treeItems . ' mt on mr.`id` = mt.`master` ' . ' where `slave` = :id ';
        } else {
            $sql = 'select mr.*, mt.linkdate, mt.linktitle, mt.linktext from ' . $resources . ' mr inner join ' .
                $treeItems . ' mt on mr.`id` = mt.`slave` ' . ' where `master` = :id union'.
                ' select mr.*, mt.linkdate, mt.linktitle, mt.linktext from ' .
                $resources . ' mr inner join ' . $treeItems . ' mt on mr.`id` = mt.`master`  where `slave` = :id ';
        }
        $sql .= 'order by `' . $sortBy .'` ' .$sortDir;
        if ($limit) {
            $sql .= ' limit '.$limit;
        }


        $query =  new xPDOCriteria($this->modx, $sql, [':id' => $id]);
        $query->stmt->execute();
        $resMaster = $query->stmt->fetchAll(PDO::FETCH_ASSOC);


//        $resMaster=[];
//        $resSlave=[];
//        if ($linkWay >= 0) {
//            $q = $this->modx->newQuery('modTreeItem');
//            $q->innerJoin('modResource', 'ResourceSlave');
//            $q->select(['ResourceSlave.*']);
//            $q->select(['modTreeItem.linkdate', 'modTreeItem.linktitle', 'modTreeItem.linktext']);
//
//            $q->where([
//                'master' => $id,
//                'active' => 1,
//                'ResourceSlave.published' => 1,
//            ]);
//            $q->prepare();
//            $q->stmt->execute();
//            $resMaster = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
//        }
//
//        if ($linkWay<=0) {
//            $q = $this->modx->newQuery('modTreeItem');
//            $q->innerJoin('modResource', 'ResourceMaster');
//            $q->select(['ResourceMaster.*']);
//            $q->select(['modTreeItem.linkdate', 'modTreeItem.linktitle', 'modTreeItem.linktext']);
//
//            $q->where([
//                'slave' => $id,
//                'active' => 1,
//                'ResourceMaster.published' => 1,
//            ]);
//            $q->prepare();
//            $q->stmt->execute();
//            $resSlave = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
//        }
//        $resMaster = array_merge($resMaster, $resSlave);
        for ($i = 0; $i < count($resMaster); $i++){
            $this->formatDates($resMaster[$i]);
        }

        return $this->success('',$resMaster);

    }

    private function formatDates(array &$resourceArray) {
        $format = $this->modx->getOption('modtree_date_format') .' '. $this->modx->getOption('modtree_time_format');

        if (!empty($resourceArray['pub_date']) && $resourceArray['pub_date'] != '0000-00-00 00:00:00') {
            $resourceArray['pub_date'] = date($format, strtotime($resourceArray['pub_date']));
        } else {
            $resourceArray['pub_date'] = '';
        }
        if (!empty($resourceArray['unpub_date']) && $resourceArray['unpub_date'] != '0000-00-00 00:00:00') {
            $resourceArray['unpub_date'] = date($format, strtotime($resourceArray['unpub_date']));
        } else {
            $resourceArray['unpub_date'] = '';
        }
        if (!empty($resourceArray) && $resourceArray['publishedon'] != '0000-00-00 00:00:00') {
            $resourceArray['publishedon'] = date($format, strtotime($resourceArray['publishedon']));
        } else {
            $resourceArray['publishedon'] = '';
        }
        if (!empty($resourceArray) && $resourceArray['linkdate'] != '0000-00-00 00:00:00') {
            $resourceArray['linkdate'] = date($format, strtotime($resourceArray['linkdate']));
        } else {
            $resourceArray['linkdate'] = '';
        }    }

}

return 'modTreeTreeGetListProcessor';