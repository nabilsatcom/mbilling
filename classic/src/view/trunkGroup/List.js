/**
 * Classe que define a lista de "trunkGroup"
 *
 * =======================================
 * ###################################
 *
 *
 * 
 */
Ext.define('MBilling.view.trunkGroup.List', {
    extend: 'Ext.ux.grid.Panel',
    alias: 'widget.trunkgrouplist',
    store: 'TrunkGroup',
    fieldSearch: 'name',
    initComponent: function() {
        var me = this;
        me.columns = [{
            header: t('ID'),
            dataIndex: 'id',
            flex: 1,
            hidden: true,
            hideable: App.user.isAdmin
        }, {
            header: t('Name'),
            dataIndex: 'name',
            flex: 4
        }, {
            header: t('Type'),
            dataIndex: 'type',
            renderer: function(value) {
                switch (value) {
                    case 1:
                        value = t('Orden by Time & delay 10 Sec');
                        break;
                    case 2:
                        value = t('Orden by Time & delay 30 Sec');
                        break;
                    case 3:
                        value = t('Orden by Time & delay 1 Min');
                        break;
                    case 4:
                        value = t('Orden by Time & delay 2 Min');
                        break;
                    case 5:
                        value = t('Orden by Time & delay 3 Min');
                        break;
                    case 6:
                        value = t('Orden by Time & delay 5 Min');
                        break;
                    case 7:
                        value = t('Orden by Time & delay 10 Min');
                        break;
                    case 8:
                        value = t('Orden by Time & delay 15 Min');
                        break;
                    case 9:
                        value = t('Orden by Time & delay 30 Min');
                        break;
                    case 10:
                        value = t('Orden by Time & delay 1 Hour');
                        break;
                    case 11:
                        value = t('Orden by Time & delay 2 Hour');
                        break;
                    case 12:
                        value = t('Orden by Time & delay 4 Hour');
                        break;
                    case 13:
                        value = t('Random');
                        break;
                    case 14:
                        value = t('LCR');
                        break;
                    }
                return value
            },
            flex: 5,
            filter: {
                type: 'list',
                options: [
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
            }
        }, {
            header: t('Limit Conected'),
            dataIndex: 'limit_ok',
            flex: 2
        }, {
            header: t('Limit Failed'),
            dataIndex: 'limit_nok',
            flex: 2
        }]
        me.callParent(arguments);
    }
});