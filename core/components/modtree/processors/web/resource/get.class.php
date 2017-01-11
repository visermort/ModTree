<?php

class modTreeResourceGetProcessor extends  modProcessor
{

    public function process()
    {
        $id = $this->getProperty('id');
        //$object = $this->modx->getObject('modResource', $id);
        $q = $this->modx->newQuery('modResource');
        $q->select(['modResource.*']);
        $q->where(['id' => $id]);
        $q->prepare();
        $q->stmt->execute();
        $object = $q->stmt->fetch(PDO::FETCH_ASSOC);

        if ($object) {
            //найдём tv поля
            $q = $this->modx->newQuery('modTemplateVarResource');
            $q->innerJoin('modTemplateVar', 'TemplateVar');
            $q->select(['modTemplateVarResource.value', 'TemplateVar.name']);
            $q->where(['contentid' => $id]);
            $q->prepare();
            $q->stmt->execute();
            $tvValues = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($tvValues as $tvValue) {
                $object[$tvValue['name']] = $tvValue['value'];
            }
        }
        $this->formatDates($object);

        return $this->success('',$object);

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