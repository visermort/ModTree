<?php

class modTreeResourceGetProcessor extends  modProcessor
{

    private $queryText;
    private $queryCountText;

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
        $page = ($this->getProperty('page'))? $this->getProperty('page') : 1;
        $searchParams = json_decode($this->getProperty('searchParams'));
        $paginateList = $this->getProperty('paginateList');
        $queryLinks = $this->getProperty('queryLinks');
        $linkWay = $this->getProperty('linkWay');


        //$page = 2;
        //$limit = 2;

        $offset = ($limit == 0 || $page < 2)? 0 : $limit * ($page - 1);
        // Build queryes


//        $query = $this->modx->newQuery('modResource');
//        $queryCount = $this->modx->newQuery('modResource');
//        $query->select(['modResource.*']);
//        $queryCount->select(['count(*)']);
//
//        $query->where(['published'=> 1, 'deleted' => 0,]);
//        $queryCount->where(['published'=> 1, 'deleted' => 0,]);
//        if ($parent) {
//            $query->where(['parent'=> $parent]);
//            $queryCount->where(['parent'=> $parent]);
//        }
//        foreach ($searchParams as $searchParam) {
//            $query->where([$searchParam->name.':like' => '%'.$searchParam->value.'%']);
//            $queryCount->where([$searchParam->name.':like' => '%'.$searchParam->value.'%']);
//        }
//        $query->sortby($sortBy, $sortDir);
//        if ($limit > 0) {
//            $query->limit($limit, $offset);
//        }
        if ($queryLinks > 0) {
            //ищем связанные ресурсы
            $resMaster = $this->makeQueryLinks($id, $linkWay, $limit, $offset, $sortBy, $sortDir, false);
            $count = $this->makeQueryLinks($id, $linkWay, $limit, $offset, $sortBy, $sortDir, true);
        } else {
            //ищем просто ресурсы
            $resMaster = $this->makeQueryResource($searchParams, $id, $limit, $offset, $sortBy, $sortDir, false);
            $count = $this->makeQueryResource($searchParams, $id, $limit, $offset, $sortBy, $sortDir, true);
        }

        $pages = ($limit == 0) ? 0 : ceil($count/$limit);


        for ($i = 0; $i < count($resMaster); $i++){
            $this->formatDates($resMaster[$i]);
        }

        return $this->success('',[
            'pagination' => [
                'id' => $id,
                'count' => $count,
                'countResult' => count($resMaster),
                'limit' => $limit,
                'page' => $page,
                'pages' => $pages,
                'offset' => $offset,
                'buttons' => $this->makePaginate($page, $pages, $paginateList),
                'searchParams' => $searchParams,
                'queryText' => $this->queryText,
                'queryCountText' => $this->queryCountText,
                'queryLinks' => $queryLinks,
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
    }

    private function makePaginate($page, $pages, $paginateList)
    {
        if ($pages < 2) {
            return;
        }
        $buttons = [];
        if (true){
            //кнопки в виде списка
            for($i = 0; $i < $pages; $i++) {
                $buttons[] = [
                    'page' => $i+1,
                    'current' => $i+1 == $page,
                ];
            }
        } else {
            //4 кнопки

        }
        return $buttons;

    }

    private function makeQueryResource($searchParams, $parent, $limit, $offset, $sortBy, $sortDir, $count)
    {
        $query = $this->modx->newQuery('modResource');
        if (!$count) {
            $query->select(['modResource.*']);
        } else {
            $query->select(['count(*)']);
        }

        $query->where(['published'=> 1, 'deleted' => 0,]);
        if ($parent) {
            $query->where(['parent'=> $parent]);
        }
        foreach ($searchParams as $searchParam) {
            $query->where([$searchParam->name.':like' => '%'.$searchParam->value.'%']);
        }
        if (!$count) {
            $query->sortby($sortBy, $sortDir);
            if ($limit > 0) {
                $query->limit($limit, $offset);
            }
        }
        $query->prepare();
        $query->stmt->execute();
        if ($count) {
            $this->queryCountText = $query->toSQL(true);
            return (int) $query->stmt->fetchColumn();
        } else {
            $this->queryText = $query->toSQL(true);
            return $query->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }


    private function makeQueryLinks($parent, $linkWay, $limit, $offset, $sortBy, $sortDir, $count)
    {
        $resources = $this->modx->getTableName('modResource');
        $treeItems = $this->modx->getTableName('modTreeItem');
        if ($count) {
            if ($linkWay > 0) {
                $sql = 'select mr.id from ' . $resources . ' mr INNER JOIN ' .
                    $treeItems . ' mt on mr.`id` = mt.`slave`' . ' where '.
                    ' `master` = :id and `published` = 1 and `deleted` = 0 ';
            } elseif ($linkWay < 0 ) {
                $sql = 'select mr.id from ' .  $resources . ' mr inner join ' .
                    $treeItems . ' mt on mr.`id` = mt.`master` ' . ' where '.
                    ' `master` = :id and `published` = 1 and `deleted` = 0 ';
            } else {
                $sql = 'select mr.id from ' . $resources . ' mr inner join ' .
                    $treeItems . ' mt on mr.`id` = mt.`slave` ' . ' where '.
                    ' `master` = :id  and `published` = 1 and `deleted` = 0 union'.
                    ' select mr.id from ' .
                    $resources . ' mr inner join ' . $treeItems . ' mt on mr.`id` = mt.`master`  where '.
                    ' `slave` = :id and `published` = 1 and `deleted` = 0 ';
            }
            $sql = 'select count(*) from (' . $sql . ') as tt';
        } else {
            if ($linkWay > 0) {
                $sql = 'SELECT mr.*,mt.linkdate,mt.linktitle,mt.linktext FROM ' . $resources . ' mr INNER JOIN ' .
                    $treeItems . ' mt on mr.`id` = mt.`slave`' . ' where '.
                    ' `master` = :id and `published` = 1 and `deleted` = 0 ';
            } elseif ($linkWay < 0 ) {
                $sql = 'select mr.*,mt.linkdate,mt.linktitle,mt.linktext from ' . $resources . ' mr inner join ' .
                    $treeItems . ' mt on mr.`id` = mt.`master` ' . ' where '.
                    ' `slave` = :id and `published` = 1 and `deleted` = 0 ';
            } else {
                $sql = 'select mr.*, mt.linkdate, mt.linktitle, mt.linktext from ' . $resources . ' mr inner join ' .
                    $treeItems . ' mt on mr.`id` = mt.`slave` ' . ' where '.
                    ' `master` = :id and `published` = 1 and `deleted` = 0 union'.
                    ' select mr.*, mt.linkdate, mt.linktitle, mt.linktext from ' .
                    $resources . ' mr inner join ' . $treeItems . ' mt on mr.`id` = mt.`master`  where '.
                    ' `slave` = :id and `published` = 1 and `deleted` = 0 ';
            }

            $sql .= 'order by `' . $sortBy .'` ' .$sortDir;
            if ($limit) {
                $sql .= ' limit '.$offset.','.$limit;
            }
        }
        if ($count) {
            $this->queryCountText = $sql;
        } else {
            $this->queryText = $sql;
        }
        $query = $this->modx->prepare($sql);
        $query->bindParam(':id', $parent);
        $query->execute();
        if ($count) {
            //$this->queryCountText = $query->toSQL(true);
            return (int) $query->fetchColumn();
        } else {
           // $this->queryText = $query->toSQL(true);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}

return 'modTreeResourceGetProcessor';