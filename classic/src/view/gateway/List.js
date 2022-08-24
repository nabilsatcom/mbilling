/**
 * Classe que define a lista de "Gateway"
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 */
 Ext.define('MBilling.view.gateway.List', {
    extend: 'Ext.ux.grid.Panel',
    alias: 'widget.gatewaylist',
    store: 'Gateway',
    fieldSearch: 'gatewaycode',
    initComponent: function() {
        var me = this;

        me.columns = me.columns || [{
            header: t('ID'),
            dataIndex: 'id',
            flex: 1,
            hidden: true,
            hideable: App.user.isAdmin
        }, {
            header: t('Name'),
            dataIndex: 'gatewaycode',
            flex: 3
        }, {
            header: t('Add prefix'),
            dataIndex: 'gatewayprefix',
            flex: 2,
            hidden: window.isTablet
        }, {
            header: t('Remove prefix'),
            dataIndex: 'removeprefix',
            flex: 2,
            hidden: window.isTablet
        }, {
            header: t('Host'),
            dataIndex: 'host',
            flex: 2,
            hidden: window.isTablet
        }, {
            xtype: 'templatecolumn',
            tpl: '{idProviderprovider_name}',
            header: t('Provider'),
            dataIndex: 'id_provider',
            comboFilter: 'providercombo',
            flex: 2,
            hidden: window.isTablet
        }, {
            header: t('Time used'),
            renderer: Helper.Util.formatsecondsToTime,
            dataIndex: 'secondusedreal',
            flex: 3,
            hidden: window.isTablet
        }, {
            header: t('Status'),
            dataIndex: 'status',
            renderer: Helper.Util.formatBooleanActive,
            comboFilter: 'booleancombo',
            flex: 1,
            filter: {
                type: 'list',
                options: [
                    [1, t('Active')],
                    [0, t('Inactive')]
                ]
            }
        }, {
            header: t('Creation date'),
            renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s'),
            dataIndex: 'creationdate',
            flex: 3,
            hidden: window.isTablet
        }];
        me.callParent(arguments);
    }
});