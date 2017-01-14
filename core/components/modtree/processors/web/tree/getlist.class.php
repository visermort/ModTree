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
        $page = ($this->getProperty('page'))? $this->getProperty('page') : 1;

        //$page = 2;
        //$limit = 2;

        $offset = ($limit == 0 || $page < 2)? 0 : $limit * ($page - 1);
        // Build queryes

        $resources = $this->modx->getTableName('modResource');
        $treeItems = $this->modx->getTableName('modTreeItem');
        if ($linkWay > 0) {
            $sqlCount = 'select mr.id from ' . $resources . ' mr INNER JOIN ' .
                $treeItems . ' mt on mr.`id` = mt.`slave`' . ' where `master` = :id ';
            $sql = 'SELECT mr.*,mt.linkdate,mt.linktitle,mt.linktext FROM ' . $resources . ' mr INNER JOIN ' .
                $treeItems . ' mt on mr.`id` = mt.`slave`' . ' where `master` = :id ';
        } elseif ($linkWay < 0 ) {
            $sqlCount = 'select mr.id from ' .  $resources . ' mr inner join ' .
                $treeItems . ' mt on mr.`id` = mt.`master` ' . ' where `slave` = :id ';
            $sql = 'select mr.*,mt.linkdate,mt.linktitle,mt.linktext from ' . $resources . ' mr inner join ' .
                $treeItems . ' mt on mr.`id` = mt.`master` ' . ' where `slave` = :id ';
        } else {
            $sqlCount = 'select mr.id from ' . $resources . ' mr inner join ' .
                $treeItems . ' mt on mr.`id` = mt.`slave` ' . ' where `master` = :id union'.
                ' select mr.id from ' .
                $resources . ' mr inner join ' . $treeItems . ' mt on mr.`id` = mt.`master`  where `slave` = :id ';
            $sql = 'select mr.*, mt.linkdate, mt.linktitle, mt.linktext from ' . $resources . ' mr inner join ' .
                $treeItems . ' mt on mr.`id` = mt.`slave` ' . ' where `master` = :id union'.
                ' select mr.*, mt.linkdate, mt.linktitle, mt.linktext from ' .
                $resources . ' mr inner join ' . $treeItems . ' mt on mr.`id` = mt.`master`  where `slave` = :id ';
        }
        $sqlCount = 'select count(*) from (' . $sqlCount . ') as tt';

        //doto published deleted
        $sql .= 'order by `' . $sortBy .'` ' .$sortDir;
        if ($limit) {
            $sql .= ' limit '.$offset.','.$limit;
        }

        $queryCount = $this->modx->prepare($sqlCount);
        $queryCount->bindParam(':id', $id);
        $queryCount->execute();
        $count = (int) $queryCount->fetchColumn();
        $pages = ($limit == 0) ? 0 : ceil($count/$limit);

        //$query =  new xPDOCriteria($this->modx, $sql, [':id' => $id]);
        $query = $this->modx->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();

        $resMaster = $query->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($resMaster); $i++){
            $this->formatDates($resMaster[$i]);
        }

        return $this->success('',[
            'pagination' => [
                'count' => $count,
                'limit' => $limit,
                'page' => $page,
                'pages' => $pages,
                'offset' => $offset,
            ],
            'items' => $resMaster,
        ]);

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