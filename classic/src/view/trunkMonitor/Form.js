/**
 * Classe que define o form de "TrunkMonitor"
 * =======================================
 * ###################################
 * 
 */
Ext.define('MBilling.view.trunkMonitor.Form', {
    extend: 'Ext.ux.form.Panel',
    alias: 'widget.trunkmonitorform',
    initComponent: function() {
        var me = this;
        me.items = [{
            xtype: 'trunklookup',
            ownerForm: me,
            name: 'id_trunk',
            fieldLabel: t('Trunk'),
            hidden: !App.user.isAdmin,
            allowBlank: !App.user.isAdmin
        }, {
            xtype: 'trunkgrouplookup',
            ownerForm: me,
            name: 'id_trunk_group',
            fieldLabel: t('Trunk groups'),
            hidden: !App.user.isAdmin,
            allowBlank: !App.user.isAdmin
        }, {
            xtype: 'datetimefield',
            name: 'starttime',
            fieldLabel: t('Date'),
            format: 'Y-m-d H:i:s',
            value: new Date()
        }, {
            xtype: 'numberfield',
            name: 'OK',
            fieldLabel: t('200'),
            value: '0',
            minValue: 0,
            flex: 4
        }, {
            xtype: 'numberfield',
            name: 'NOK',
            fieldLabel: t('Failed'),
            value: '0',
            minValue: 0,
            flex: 4
        }];
        me.callParent(arguments);
    }
});