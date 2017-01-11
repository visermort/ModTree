<?php

/**
 * The home manager controller for modTree.
 *
 */
class modTreeHomeManagerController extends modExtraManagerController
{
    /** @var modTree $modTree */
    public $modTree;


    /**
     *
     */
    public function initialize()
    {
        $path = $this->modx->getOption('modtree_core_path', null,
                $this->modx->getOption('core_path') . 'components/modtree/') . 'model/modtree/';
        $this->modTree = $this->modx->getService('modtree', 'modTree', $path);
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('modtree:default');
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('modtree');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->modTree->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->modTree->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        $this->addJavascript($this->modTree->config['jsUrl'] . 'mgr/modtree.js');
        $this->addJavascript($this->modTree->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->modTree->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->modTree->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->modTree->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->modTree->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->modTree->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        modTree.config = ' . json_encode($this->modTree->config) . ';
        modTree.config.connector_url = "' . $this->modTree->config['connectorUrl'] . '";
        Ext.onReady(function() {
            MODx.load({ xtype: "modtree-page-home"});
        });
        </script>
        ');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->modTree->config['templatesPath'] . 'home.tpl';
    }
}