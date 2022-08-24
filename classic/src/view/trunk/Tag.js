/**
 * Class to define tag of "trunk"
 *
 * 
 */
Ext.define('MBilling.view.trunk.Tag', {
    extend: 'Ext.form.field.Tag',
    alias: 'widget.trunktag',
    name: 'id_trunk',
    fieldLabel: t('Trunk'),
    displayField: 'trunkcode',
    valueField: 'id',
    filterPickList: true,
    initComponent: function() {
        var me = this;
        me.store = Ext.create('MBilling.store.Trunk', {
            proxy: {
                type: 'uxproxy',
                module: 'trunk',
                limitParam: undefined
            }
        });
        me.callParent(arguments);
    }
});