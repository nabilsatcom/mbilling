/**
 * Classe que define a lista de "TrunkMonitor"
 * =======================================
 * ###################################
 *
 * 
 */
 Ext.define('MBilling.view.trunkMonitor.List', {
    extend: 'Ext.ux.grid.Panel',
    alias: 'widget.trunkmonitorlist',
    store: 'TrunkMonitor',
    fieldSearch: 'idTrunkGroupname.id_trunk_group',
    refreshTime: 5,
    initComponent: function() {
        var me = this;
        me.buttonCsv = false;
        me.allowPrint = false;
        me.buttonUpdateLot = false;
        me.textDelete = t('Delete');
        me.refreshTime = (localStorage && localStorage.getItem('callonlinerefresh')) || me.refreshTime;
        me.extraButtons = [{
            xtype: 'numberfield',
            field: 'jmlhBrg',
            fieldLabel: t('Refresh rate'),
            editable: false,
            minValue: 5,
            labelWidth: 90,
            width: 150,
            selectOnFocus: true,
            allowDecimals: true,
            decimalPrecision: 2,
            value: me.refreshTime,
            listeners: {
                change: function(field) {
                    if (field.value > 0) {
                        me.refreshTime = field.value;
                        localStorage.setItem('callonlinerefresh', field.value);
                    };
                }
            }
        }];
        me.columns = me.columns || [{
            header: t('ID'),
            dataIndex: 'id',
            flex: 1,
            hidden: true,
            hideable: App.user.isAdmin
    }, {
            xtype: 'templatecolumn',
            tpl: '{idTrunktrunkcode}',
            header: t('Trunk'),
            dataIndex: 'id_trunk',
            comboFilter: 'trunkcombo',
            flex: 3,
            hideable: App.user.isAdmin
    }, {
            xtype: 'templatecolumn',
            tpl: '{idTrunkGroupname}',
            header: t('Trunk groups'),
            dataIndex: 'id_trunk_group',
            comboFilter: 'trunkgroupcombo',
            flex: 3,
            hideable: App.user.isAdmin
    }, {
            header: t('Date'),
            renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s'),
            dataIndex: 'starttime',
            flex: 4,
            hideable: App.user.isAdmin
        }];
        me.callParent(arguments);
    }
});
Ext.define('MBilling.view.trunkMonitor.List2', {
    extend: 'Ext.ux.grid.Panel',
    alias: 'widget.trunkmonitorlist2',
    store: 'TrunkMonitor',
    refreshTime: 5,
    initComponent: function() {
        var me = this;
        me.buttonCsv = false;
        me.allowPrint = false;
        me.buttonUpdateLot = false;
        me.allowCreate = false;
        me.buttonCleanFilter = false;
        me.allowUpdate = false;
        me.allowDelete = false;
        me.columns = me.columns || [{
            header: t('ID'),
            dataIndex: 'id',
            flex: 1,
            hidden: true,
            hideable: App.user.isAdmin
        }, {
            xtype: 'templatecolumn',
            tpl: '{idTrunktrunkcode}',
            header: t('Trunk'),
            dataIndex: 'id_trunk',
            comboFilter: 'trunkcombo',
            flex: 3,
            hideable: App.user.isAdmin
        }, {
            xtype: 'templatecolumn',
            tpl: '{idTrunkGroupname}',
            header: t('Trunk groups'),
            dataIndex: 'id_trunk_group',
            comboFilter: 'trunkgroupcombo',
            flex: 3,
            hideable: App.user.isAdmin
        }, {
            header: t('Date'),
            renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s'),
            dataIndex: 'starttime',
            flex: 4,
            hideable: App.user.isAdmin
        }, {
            header: t('Count'),
            dataIndex: 'count',
            hideable: !App.user.isClient && !App.user.isAgent,
            flex: 1
        }, {
            header: t('ACD'),
            dataIndex: 'acd',
            hideable: !App.user.isClient && !App.user.isAgent,
            flex: 1
        }];
        me.sessionLoad = Ext.create('Ext.util.DelayedTask', function() {
            me.store.load();
        }, me);
        me.callParent(arguments);
        me.store.on('load', me.onLoadStore, me);
    },
    onLoadStore: function() {
        var me = this;
        me.onDeactivateModule();
        me.onActivateModule();
    },
    onRender: function() {
        var me = this;
        if (Ext.isObject(me.module)) {
            me.module.on('activate', me.onActivateModule, me);
            me.module.on('deactivate', me.onDeactivateModule, me);
            me.module.on('close', me.onCloseModule, me);
        };
        me.callParent(arguments);
    },
    onActivateModule: function() {
        this.sessionLoad && this.sessionLoad.delay(this.refreshTime * 500);
    },
    onDeactivateModule: function() {
        this.sessionLoad && this.sessionLoad.cancel();
    },
    onCloseModule: function() {
        this.onDeactivateModule();
        this.sessionLoad = null;
    }
});