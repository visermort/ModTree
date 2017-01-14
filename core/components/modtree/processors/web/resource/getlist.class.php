<?php

class modTreeResourceGetProcessor extends  modProcessor
{


    public function checkPermissions()
    {
        return true;
    }

    public function process()
    {
        $parent = $this->getProperty('parent');
        $limit = $this->getProperty('limit');
        $sortBy = $this->getProperty('sortBy');
        $sortDir = $this->getProperty('sortDir');
        $page = ($this->getProperty('page'))? $this->getProperty('page') : 1;
        $searchParams = json_decode($this->getProperty('searchParams'));

        //$page = 2;
        //$limit = 2;

        $offset = ($limit == 0 || $page < 2)? 0 : $limit * ($page - 1);
        // Build queryes


        $query = $this->modx->newQuery('modResource');
        $queryCount = $this->modx->newQuery('modResource');
        $query->select(['modResource.*']);
        $queryCount->select(['count(*)']);

        $query->where(['published'=> 1, 'deleted' => 0,]);
        $queryCount->where(['published'=> 1, 'deleted' => 0,]);
        if ($parent) {
            $query->where(['parent'=> $parent]);
            $queryCount->where(['parent'=> $parent]);
        }
        foreach ($searchParams as $searchParam) {
            $query->where([$searchParam->name.':like' => '%'.$searchParam->value.'%']);
            $queryCount->where([$searchParam->name.':like' => '%'.$searchParam->value.'%']);
        }
        $query->sortby($sortBy, $sortDir);
        if ($limit > 0) {
            $query->limit($limit, $offset);
        }
        $queryCount->prepare();
        $queryCount->stmt->execute();
        $count = (int) $queryCount->stmt->fetchColumn();
        $pages = ($limit == 0) ? 0 : ceil($count/$limit);

        $query->prepare();
        $queryText = $query->toSQL(true);
        $query->stmt->execute();
        $resMaster = $query->stmt->fetchAll(PDO::FETCH_ASSOC);


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
                'searchParams' => $searchParams,
                'queryText' => $queryText,
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

}

return 'modTreeResourceGetProcessor';