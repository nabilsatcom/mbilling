/**
 * Classe que define o form de "Campaign"
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
 * 28/10/2012
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