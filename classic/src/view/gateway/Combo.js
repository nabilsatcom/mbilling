/**
 * Classe que define a combo de "gatewaycombo"
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 */
 Ext.define('MBilling.view.gateway.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.gatewaycombo',
    name: 'id_gateway',
    fieldLabel: t('Gateway'),
    forceSelection: true,
    editable: false,
    displayField: 'gatewaycode',
    valueField: 'id',
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
Ext.define('MBilling.view.gateway.ComboBackup', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.gatewaycombobackup',
    name: 'failover_gateway',
    fieldLabel: t('Failover gateway'),
    displayField: 'gatewaycode',
    valueField: 'id',
    value: 0,
    limitParam: undefined,
    forceSelection: true,
    editable: true,
    extraValues: [{
        id: 0,
        gatewaycode: t('Undefined')
    }],
    listeners: {
        focus: function(combo) {
            combo.expand();
        }
    },
    //permite buscar sem limite de tronco backup
    initComponent: function() {
        var me = this;
        me.store = Ext.create('MBilling.store.Gateway', {
            proxy: {
                type: 'uxproxy',
                module: 'gateway',
                limitParam: undefined
            }
        });
        me.on('render', me.loadStore, me);
        me.callParent(arguments);
    },
    loadStore: function(combo) {
        var me = this,
            store = combo.store,
            record;
        store.load({
            callback: function() {
                if (me.extraValues.length) {
                    store.insert(0, me.extraValues);
                }
            }
        });
    }
});