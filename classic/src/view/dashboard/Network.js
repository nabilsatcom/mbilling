/**
 * Classe que define o grafico de "refill"
 *
 * =======================================
 * ###################################
 *
 *
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusBilling. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v3
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnussolution/magnusbilling7/issues
 * =======================================
 * Magnusbilling.org <info@magnussolution.com>
 * 13/02/2012
 */
Ext.define('MBilling.view.dashboard.Network', {
    extend: 'Ext.panel.Panel',
    xtype: 'network',
    requires: ['Ext.chart.CartesianChart', 'Ext.chart.axis.Category', 'Ext.chart.axis.Numeric', 'Ext.chart.series.Line', 'Ext.chart.interactions.PanZoom', 'Ext.ProgressBar'],
    cls: 'dashboard-main-chart shadow',
    reference: 'networkpanel',
    layout: {
        type: 'vbox',
        align: 'stretch'
    },
    initComponent: function() {
        var me = this;
        me.items = [{
            flex: 1,
            layout: 'fit',
            height: 150,
            items: [{
                xtype: 'networkchart'
            }]
        }, {
            cls: 'graph-analysis-left',
            layout: {
                type: 'hbox',
                align: 'stretch'
            },
            items: [{
                flex: 1,
                cls: 'dashboard-graph-analysis-left',
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                items: [{
                    flex: 1,
                    padding: '10 0 10 0',
                    layout: {
                        type: 'hbox',
                        align: 'stretch'
                    },
                    items: [{
                        xtype: 'component',
                        flex: 1,
                        cls: 'top-info-container',
                        reference: 'cpuMediaUso',
                        html: '',
                        padding: '15 10 10 0'
                    }]
                }, {
                    xtype: 'progressbar',
                    flex: 1,
                    cls: 'left-top-text progressbar-no-text',
                    height: 3,
                    reference: 'cpuPercent',
                    hideMode: 'offsets',
                    margin: '0 15 0 0',
                    maxHeight: 5,
                    minHeight: 3,
                    value: 0
                }, {
                    xtype: 'component',
                    flex: 1,
                    cls: 'left-top-text',
                    reference: 'cpuModel',
                    html: '',
                    padding: '15 5 5 0',
                    layout: {
                        type: 'hbox',
                        align: 'stretch'
                    }
                }]
            }, {
                flex: 1,
                cls: 'graph-analysis-right',
                margin: '15 0 0 0',
                padding: '0 0 0 15',
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                itemPadding: '0 0 10 0',
                items: [{
                    flex: 1,
                    layout: {
                        type: 'hbox',
                        align: 'stretch'
                    },
                    padding: '0 0 10 0',
                    items: [{
                        xtype: 'component',
                        flex: 1,
                        cls: 'graph-analysis-right-inner-container',
                        html: t('Mem total')
                    }, {
                        xtype: 'component',
                        flex: 1,
                        padding: '0 10 0 0',
                        cls: 'graph-analysis-right-inner-container right-value',
                        html: '0',
                        reference: 'memTotal'
                    }]
                }, {
                    flex: 1,
                    layout: {
                        type: 'hbox',
                        align: 'stretch'
                    },
                    padding: '0 0 10 0',
                    items: [{
                        xtype: 'component',
                        flex: 1,
                        cls: 'graph-analysis-right-inner-container',
                        html: t('Mem used')
                    }, {
                        xtype: 'component',
                        flex: 1,
                        padding: '0 10 0 0',
                        cls: 'graph-analysis-right-inner-container right-value',
                        html: '0',
                        reference: 'memUsed'
                    }]
                }, {
                    flex: 1,
                    layout: {
                        type: 'hbox',
                        align: 'stretch'
                    },
                    padding: '0 0 10 0',
                    items: [{
                        xtype: 'component',
                        flex: 1,
                        cls: 'graph-analysis-right-inner-container',
                        html: t('Uptime')
                    }, {
                        xtype: 'component',
                        flex: 1,
                        padding: '0 10 0 0',
                        cls: 'graph-analysis-right-inner-container right-value',
                        html: '0',
                        reference: 'uptime'
                    }]
                }]
            }]
        }]
        me.callParent(arguments);
    }
});