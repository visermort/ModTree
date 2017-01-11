modTree.combo.resourceCombo = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'slave'
        ,hiddenName: 'slave'
        ,displayField: 'pagetitle'
        ,valueField: 'id'
        ,url: modTree.config.connectorUrl
        ,baseParams: { action: 'mgr/resources/getlist' }
        ,fields: ['id','pagetitle']
        ,pageSize: 20
        ,typeAhead: true
        ,editable: true
    });
    modTree.combo.resourceCombo.superclass.constructor.call(this,config);
};
Ext.extend(modTree.combo.resourceCombo, MODx.combo.ComboBox);
Ext.reg('modtree-combo-resourcecombo', modTree.combo.resourceCombo);

modTree.window.CreateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'modtree-item-window-create';
    }
    Ext.applyIf(config, {
        title: _('modtree_item_create'),
        width: 600,
        autoHeight: true,
        url: modTree.config.connector_url,
        action: 'mgr/item/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    modTree.window.CreateItem.superclass.constructor.call(this, config);
};
Ext.extend(modTree.window.CreateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            // xtype: 'textfield',
            xtype: 'modtree-combo-resourcecombo',
            fieldLabel: _('modtree_item_master'),
            name: 'master',
            hiddenName: 'master',
            id: config.id + '-master',
            anchor: '99%',
            allowBlank: false
        },
            {
            xtype: 'modtree-combo-resourcecombo',
            fieldLabel: _('modtree_item_slave'),
            name: 'slave',
            hiddenName: 'slave',
            id: config.id + '-slave',
            anchor: '99%',
            allowBlank: false
        }
        ,
        {
            xtype: 'xdatetime',
            dateFormat: 'd.m.Y',
            timeFormat: 'H:i',
            fieldLabel: _('modtree_item_linkdate'),
            name: 'linkdate',
            id: config.id + '-linkdate',
            anchor: '99%'
        },
        {
            xtype: 'textfield',
            fieldLabel: _('modtree_item_linktitle'),
            name: 'linktitle',
            id: config.id + '-linktitle',
            anchor: '99%'
        },
        {
            xtype: 'textarea',
            fieldLabel: _('modtree_item_linktext'),
            name: 'linktext',
            id: config.id + '-linktext',
            heigth: 400,
            anchor: '99%'
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('modtree-item-window-create', modTree.window.CreateItem);


modTree.window.UpdateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'modtree-item-window-update';
    }
    Ext.applyIf(config, {
        title: _('modtree_item_update'),
        width: 600,
        autoHeight: true,
        url: modTree.config.connector_url,
        action: 'mgr/item/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    modTree.window.UpdateItem.superclass.constructor.call(this, config);
};
Ext.extend(modTree.window.UpdateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id'
        }, {
            xtype: 'modtree-combo-resourcecombo',
            fieldLabel: _('modtree_item_master'),
            name: 'master',
            hiddenName: 'master',
            id: config.id + '-master',
            anchor: '99%',
            allowBlank: false
        },
            {
            xtype: 'modtree-combo-resourcecombo',
            fieldLabel: _('modtree_item_slave'),
            name: 'slave',
            hiddenName: 'slave',
            id: config.id + '-slave',
            anchor: '99%',
            allowBlank: false
        },
        {
            xtype: 'xdatetime',
            //format: 'Y-m-d', // make it display correct but sends it to server as Y-m-d
            dateFormat: 'd.m.Y',
            timeFormat: 'H:i',
            //submitFormat: 'U',
            //renderer: Ext.util.Format.dateRenderer('Y-m-d'),
            //altFormats:'U|u|m/d/Y|n/j/Y|n/j/y|m/j/y|n/d/y|m/j/Y|n/d/Y|m-d-y|m-d-Y|m/d|m-d|md|mdy|mdY|d|Y-m-d|n-j|n/j',
            fieldLabel: _('modtree_item_linkdate'),
            name: 'linkdate',
            id: config.id + '-linkdate',
            anchor: '99%'
        },
        {
            xtype: 'textfield',
            fieldLabel: _('modtree_item_linktitle'),
            name: 'linktitle',
            id: config.id + '-linktitle',
            anchor: '99%'
        },
        {
            xtype: 'textarea',
            fieldLabel: _('modtree_item_linktext'),
            name: 'linktext',
            id: config.id + '-linktext',
            heigth: 400,
            anchor: '99%'
            }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('modtree-item-window-update', modTree.window.UpdateItem);