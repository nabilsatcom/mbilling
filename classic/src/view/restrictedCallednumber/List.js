/**
 * Classe que define a lista de "RestrictedCallednumber"
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
 * 24/09/2012
 */
Ext.define('MBilling.view.restrictedCallednumber.List', {
    extend: 'Ext.ux.grid.Panel',
    alias: 'widget.restrictedcallednumberlist',
    store: 'RestrictedCallednumber',
    buttonImportCsv: true,
    fieldSearch: 'number',
    initComponent: function() {
        var me = this;
        me.columns = [{
            header: t('ID'),
            dataIndex: 'id',
            flex: 1,
            hidden: true,
            hideable: App.user.isAdmin
        }, {
            header: t('Username'),
            dataIndex: 'idUserusername',
            filter: {
                type: 'string',
                field: 'idUser.username'
            },
            flex: 2,
            hidden: App.user.isClient,
            hideable: !App.user.isClient
        }, {
            header: t('Number'),
            dataIndex: 'number',
            flex: 2
        }, {
            header: t('Direction'),
            dataIndex: 'direction',
            renderer: Helper.Util.formatDirection,
            filter: {
                type: 'list',
                options: [
                    [1, t('Outbound')],
                    [2, t('Inbound')]
                ]
            },
            flex: 1
        }, {
            header: t('Date'),
            renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s'),
            dataIndex: 'starttime',
            flex: 4
        }]
        me.callParent(arguments);
    }
});