/**
 * Classe que define a lista de "Campaign"
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
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
 * 19/09/2012
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
                        value = t('Order By Time & delay 10 Sec');
                        break;
                    case 2:
                        value = t('Order By Time & delay 30 Sec');
                        break;
                    case 3:
                        value = t('Order By Time & delay 1 MIN');
                        break;
                    case 4:
                        value = t('Order By Time & delay 2 MIN');
                        break;
                    case 5:
                        value = t('Order By Time & delay 3 MIN');
                        break;
                    case 6:
                        value = t('Order By Time & delay 5 MIN');
                        break;
                    case 7:
                        value = t('Order By Time & delay 10 MIN');
                        break;
                    case 8:
                        value = t('Order By Time & delay 15 MIN');
                        break;
                    case 9:
                        value = t('Order By Time & delay 20 MIN');
                        break;
                    case 10:
                        value = t('Order By Time & delay 30 MIN');
                        break;
                    case 11:
                        value = t('Order By Time & delay 1 Hour');
                        break;
                    case 12:
                        value = t('Order By Time & delay 2 Hour');
                        break;
                    case 13:
                        value = t('Order By Time & delay 4 Hour');
                        break;
                    case 14:
                        value = t('Random');
                        break;
                    case 15:
                        value = t('LCR');
                        break;
                    }
                return value
            },
            flex: 5,
            filter: {
                type: 'list',
                options: [
                    [1, t('Order By Time & delay 10 Sec')],
                    [2, t('Order By Time & delay 30 Sec')],
                    [3, t('Order By Time & delay 1 Min')],
                    [4, t('Order By Time & delay 2 Min')],
                    [5, t('Order By Time & delay 3 Min')],
                    [6, t('Order By Time & delay 5 Min')],
                    [7, t('Order By Time & delay 10 Min')],
                    [8, t('Order By Time & delay 15 Min')],
                    [9, t('Order By Time & delay 20 Min')],
                    [10, t('Order By Time & delay 30 Min')],
                    [11, t('Order By Time & delay 1 Hour')],
                    [12, t('Order By Time & delay 2 Hour')],
                    [13, t('Order By Time & delay 4 Hour')],
                    [14, t('Random')],
                    [15, t('LCR')]
                        ]
            }

        }, {
            header: t('Description'),
            dataIndex: 'description',
            flex: 5,
            hidden: window.isTablet
        }]
        me.callParent(arguments);
    }
});