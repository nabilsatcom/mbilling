/**
 * Class to define lookup of "user"
 *
 */
 Ext.define('MBilling.view.gateway.Lookup', {
    extend: 'Ext.ux.form.field.Lookup',
    alias: 'widget.gatewaylookup',
    name: 'id_gateway',
    fieldLabel: t('Gateway'),
    displayField: 'idGatewaygatewaycode',
    displayFieldList: 'gatewaycode',
    gridConfig: {
        xtype: 'gatewaylist',
        fieldSearch: 'gatewaycode',
        columns: [{
            header: t('Name'),
            dataIndex: 'gatewaycode'
        }]
    }
});