/**
 * Classe que define o form de "trunkGroup"
 *
 * =======================================
 * ###################################
 *
 * 
 * 
 */
Ext.define('MBilling.view.trunkGroup.Form', {
    extend: 'Ext.ux.form.Panel',
    alias: 'widget.trunkgroupform',
    fieldsHideUpdateLot: ['name', 'id_trunk'],
    initComponent: function() {
        var me = this;
        me.items = [{
            name: 'name',
            fieldLabel: t('Name'),
            hidden: App.user.isClient
        }, {
            xtype: 'combobox',
            name: 'type',
            fieldLabel: t('Type'),
            forceSelection: true,
            editable: false,
            value: 1,
            store: [
                [1, t('Orden by Time & delay 10 Sec')],
                [2, t('Orden by Time & delay 30 sec')],
                [3, t('Orden by Time & delay 1 min')],
                [4, t('Orden by Time & delay 2 min')],
                [5, t('Orden by Time & delay 3 min')],
                [6, t('Orden by Time & delay 5 min')],
                [7, t('Orden by Time & delay 10 min')],
                [8, t('Orden by Time & delay 15 min')],
                [10, t('Orden by Time & delay 30 min')],
                [11, t('Orden by Time & delay 1 Hour')],
                [11, t('Orden by Time & delay 2 Hour')],
                [12, t('Orden by Time & delay 4 Hour')],
                [13, t('Random')],
                [14, t('LCR')]
            ]
        }, {
            xtype: 'numberfield',
            name: 'limit_ok',
            fieldLabel: t('Limit 200'),
            value: '0',
            minValue: 0,
            flex: 3
        }, {
            xtype: 'numberfield',
            name: 'limit_nok',
            fieldLabel: t('Limit Failed'),
            value: '0',
            minValue: 0,
            flex: 3
        }, {
            xtype: 'fieldset',
            style: 'margin-top:10px; overflow: visible;',
            title: t('Select one or more trunks'),
            collapsible: false,
            collapsed: false,
            height: 300,
            items: [{
                xtype: 'trunktag',
                name: 'id_trunk',
                fieldLabel: t('Trunks'),
                labelWidth: 70,
                anchor: '100%',
                allowBlank: true
            }]
        }];
        me.callParent(arguments);
    }
});