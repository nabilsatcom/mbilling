/**
 * Classe que define o form de "Rate"
 *
 * =======================================
 * ###################################
 * 
 */
 Ext.define('MBilling.view.rate.Form', {
    extend: 'Ext.ux.form.Panel',
    alias: 'widget.rateform',
    fieldsHideUpdateLot: ['id_prefix'],
    initComponent: function() {
        var me = this;
        me.defaults = {
            labelWidth: 200
        };

        
        me.items = [{
            xtype: 'tabpanel',
            defaults: {
                border: false,
                defaultType: 'textfield',
                layout: 'anchor',
                bodyPadding: 5,
                defaults: {
                    labelWidth: 170,
                    labelAlign: 'right',
                    plugins: 'markallowblank',
                    allowBlank: false,
                    anchor: '100%',
                    enableKeyEvents: true
                }
            },
            items: [{
                title: t('Rate'),
                items: [{
                    xtype: 'planlookup',
                    ownerForm: me,
                    name: 'id_plan',
                    fieldLabel: t('Plan'),
                    hidden: App.user.isClient
                }, {
                    xtype: 'prefixlookup',
                    ownerForm: me,
                    name: 'id_prefix',
                    fieldLabel: t('Destination'),
                    hidden: App.user.isClient,
                    allowBlank: false
                }, {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        allowBlank: false,
                        flex: 1
                    },
                    items: [{
                        xtype: 'booleancombo',
                        ownerForm: me,
                        name: 'id_trunk_history',
                        fieldLabel: t('Trunk History'),
                        flex: 4
                    }, {
                        xtype: 'numberfield',
                        name: 'History_Number',
                        fieldLabel: t('H'),
                        value: '1',
                        minValue: 1,
                        labelWidth: 20
                    }]
                }, {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        allowBlank: false,
                        flex: 1
                    },
                    items: [{
                        xtype: 'trunkgrouplookup',
                        ownerForm: me,
                        name: 'id_trunk_group1',
                        fieldLabel: t('Trunk groups 1'),
                        flex: 4
                    }, {
                        xtype: 'booleancombo',
                        fieldLabel: t('WL1'),
                        name: 'WL1',
                        labelWidth: 20
                    }]
                }, {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        allowBlank: false,
                        flex: 1
                    },
                    items: [{
                        xtype: 'trunkgroup2lookup',
                        ownerForm: me,
                        name: 'id_trunk_group2',
                        fieldLabel: t('Trunk groups 2'),
                        flex: 4
                    }, {
                        xtype: 'booleancombo',
                        fieldLabel: t('WL2'),
                        name: 'WL2',
                        labelWidth: 20
                    }]
                }, {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        allowBlank: false,
                        flex: 1
                    },
                    items: [{
                        xtype: 'trunkgroup3lookup',
                        ownerForm: me,
                        name: 'id_trunk_group3',
                        fieldLabel: t('Trunk groups 3'),
                        flex: 4
                    }, {
                        xtype: 'booleancombo',
                        fieldLabel: t('WL3'),
                        name: 'WL3',
                        labelWidth: 20
                    }]
                }, {
                    xtype: 'moneyfield',
                    name: 'rateinitial',
                    fieldLabel: t('Sell price'),
                    mask: App.user.currency + ' #9.999.990,' + App.user.decimalPrecision,
                    readOnly: App.user.isClient,
                    hidden: App.user.hidden_prices == 1
                }, {
                    xtype: 'numberfield',
                    name: 'initblock',
                    fieldLabel: t('Initial block'),
                    value: 1,
                    minValue: 1,
                    hidden: App.user.isClient
                }, {
                    xtype: 'numberfield',
                    name: 'billingblock',
                    fieldLabel: t('Billing block'),
                    value: 1,
                    minValue: 1,
                    hidden: App.user.isClient
                }, {
                    xtype: 'numberfield',
                    name: 'minimal_time_charge',
                    fieldLabel: t('Minimum time to charge'),
                    value: 0,
                    minValue: 0,
                    hidden: App.user.isClient
                }, {
                    name: 'additional_grace',
                    fieldLabel: t('Additional time'),
                    allowBlank: true,
                    hidden: !App.user.isAdmin
                }, {
                    xtype: 'moneyfield',
                    name: 'connectcharge',
                    fieldLabel: t('Connection charge'),
                    mask: App.user.currency + ' #9.999.990,' + App.user.decimalPrecision,
                    readOnly: App.user.isClient,
                    hidden: App.user.hidden_prices == 1
                }, {
                    xtype: 'noyescombo',
                    name: 'package_offer',
                    fieldLabel: t('Include in offer'),
                    hidden: !App.user.isAdmin,
                    allowBlank: true
                }, {
                    xtype: 'booleancombo',
                    name: 'status',
                    fieldLabel: t('Status'),
                    hidden: !App.user.isAdmin,
                    allowBlank: true
                }]
            }, {
                title: t('Filter Numbers'),
                items: [{
                    xtype: 'checkboxgroup',
                    fieldLabel: t('Filter Numbers'),
                    columns: 2,
                    items: [{
                        boxLabel: 'Range Called',
                        name: 'FRCalled',
                        inputValue: '1',
                        uncheckedValue: '0',
                        checked: true
                    }, {
                        boxLabel: 'Range Caller',
                        name: 'FRCaller',
                        inputValue: '1',
                        uncheckedValue: '0',
                        checked: true
                    }, {
                        boxLabel: 'Number Called',
                        name: 'FNCalled',
                        inputValue: '1',
                        uncheckedValue: '0',
                        checked: true
                    }, {
                        boxLabel: 'Number Caller',
                        name: 'FNCaller',
                        inputValue: '1',
                        uncheckedValue: '0',
                        checked: true
                    }],
                    allowBlank: true
                },{
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        flex: 1
                    },
                    items: [{
                        xtype: 'booleancombo',
                        ownerForm: me,
                        name: '2min',
                        fieldLabel: t('Last 2 Min'),
                        flex: 4
                    }, {
                        xtype: 'numberfield',
                        name: 'att2min',
                        fieldLabel: t('Att'),
                        value: '0',
                        minValue: 0,
                        labelWidth: 20
                    }]
                },{
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        flex: 1
                    },
                    items: [{
                        xtype: 'booleancombo',
                        ownerForm: me,
                        name: '5min',
                        fieldLabel: t('Last 5 Min'),
                        flex: 4
                    }, {
                        xtype: 'numberfield',
                        name: 'att5min',
                        fieldLabel: t('Att'),
                        value: '0',
                        minValue: 0,
                        labelWidth: 20
                    }]

                },{
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        flex: 1
                    },
                    items: [{
                        xtype: 'booleancombo',
                        ownerForm: me,
                        name: '15min',
                        fieldLabel: t('Last 15 Min'),
                        flex: 4
                    }, {
                        xtype: 'numberfield',
                        name: 'att15min',
                        fieldLabel: t('Att'),
                        value: '0',
                        minValue: 0,
                        labelWidth: 20
                    }]

                },{
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        flex: 1
                    },
                    items: [{
                        xtype: 'booleancombo',
                        ownerForm: me,
                        name: '1h',
                        fieldLabel: t('Last 1 Hour'),
                        flex: 4
                    }, {
                        xtype: 'numberfield',
                        name: 'att1h',
                        fieldLabel: t('Att'),
                        value: '0',
                        minValue: 0,
                        labelWidth: 20
                    }]

                },{
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        flex: 1
                    },
                    items: [{
                        xtype: 'booleancombo',
                        ownerForm: me,
                        name: '4h',
                        fieldLabel: t('Last 4 Hour'),
                        flex: 4
                    }, {
                        xtype: 'numberfield',
                        name: 'att4h',
                        fieldLabel: t('Att'),
                        value: '0',
                        minValue: 0,
                        labelWidth: 20
                    }]

                },{
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaults: {
                        xtype: 'textfield',
                        labelAlign: 'right',
                        flex: 1
                    },
                    items: [{
                        xtype: 'booleancombo',
                        ownerForm: me,
                        name: '1d',
                        fieldLabel: t('Last 1 Day'),
                        flex: 4
                    }, {
                        xtype: 'numberfield',
                        name: 'att1d',
                        fieldLabel: t('Att'),
                        value: '0',
                        minValue: 0,
                        labelWidth: 20
                    }]
                }]
            }]
        }];
        me.callParent(arguments);
    }
});
