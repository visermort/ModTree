var modTree = function (config) {
    config = config || {};
    modTree.superclass.constructor.call(this, config);
};
Ext.extend(modTree, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('modtree', modTree);

modTree = new modTree();