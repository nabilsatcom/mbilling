/**
 * Classe que define o form de "Call"
 *
 * =======================================
 * ###################################
 * 
 * 
 */
Ext.define('MBilling.view.callOnLine.Form', {
    extend: 'Ext.ux.form.Panel',
    alias: 'widget.callonlineform',
    initComponent: function() {
        var me = this;
        me.defaults = {
            readOnly: true,
            allowBlank: true
        };
        me.items = [{
            name: 'idUserusername',
            fieldLabel: t('Username')
        }, {
            name: 'sip_account',
            fieldLabel: t('Sip user')
        }, {
            name: 'idUsercredit',
            fieldLabel: t('Credit')
        }, {
            name: 'ndiscado',
            fieldLabel: t('Number')
        }, {
            name: 'codec',
            fieldLabel: t('Codec')
        }, {
            name: 'callerid',
            fieldLabel: t('CallerID')
        }, {
            name: 'tronco',
            fieldLabel: t('Trunk'),
            hidden: App.user.isClient
        }, {
            name: 'reinvite',
            fieldLabel: t('Reinvite')
        }, {
            name: 'from_ip',
            fieldLabel: t('From IP')
        }, {
            xtype: 'textarea',
            name: 'description',
            fieldLabel: t('Description'),
            hideLabel: true,
            height: 350,
            anchor: '100%',
            hidden: !App.user.isAdmin
        }]
        me.callParent(arguments);
    }
});