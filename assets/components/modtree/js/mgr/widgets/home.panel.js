modTree.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'modtree-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('modtree') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('modtree_items'),
                layout: 'anchor',
                items: [{
                    html: _('modtree_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'modtree-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    modTree.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(modTree.panel.Home, MODx.Panel);
Ext.reg('modtree-panel-home', modTree.panel.Home);
