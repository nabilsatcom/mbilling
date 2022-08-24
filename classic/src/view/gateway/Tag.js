/**
 * Class to define tag of "gateway"
 *
 */
 Ext.define('MBilling.view.gateway.Tag', {
    extend: 'Ext.form.field.Tag',
    alias: 'widget.gatewaytag',
    name: 'id_gateway',
    fieldLabel: t('Gateway'),
    displayField: 'gatewaycode',
    valueField: 'id',
    filterPickList: true,
    initComponent: function() {
        var me = this;
        me.store = Ext.create('MBilling.store.Gateway', {
            proxy: {
                type: 'uxproxy',
                module: 'gateway',
                limitParam: undefined
            }
        });
        me.callParent(arguments);
    }
});