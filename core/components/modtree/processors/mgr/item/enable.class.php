<?php

class modTreeItemEnableProcessor extends modObjectProcessor
{
    public $objectType = 'modTreeItem';
    public $classKey = 'modTreeItem';
    public $languageTopics = array('modtree');
    //public $permission = 'save';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('modtree_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var modTreeItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('modtree_item_err_nf'));
            }

            $object->set('active', true);
            $object->save();
        }

        return $this->success();
    }

}

return 'modTreeItemEnableProcessor';
