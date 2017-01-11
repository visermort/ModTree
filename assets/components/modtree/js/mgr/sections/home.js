modTree.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'modtree-panel-home',
            renderTo: 'modtree-panel-home-div'
        }]
    });
    modTree.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(modTree.page.Home, MODx.Component);
Ext.reg('modtree-page-home', modTree.page.Home);