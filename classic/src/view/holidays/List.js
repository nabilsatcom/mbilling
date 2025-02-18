/**
 * Classe que define a lista de "holidays"
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
 * 22/12/2020
 */
Ext.define('MBilling.view.holidays.List', {
    extend: 'Ext.ux.grid.Panel',
    alias: 'widget.holidayslist',
    store: 'Holidays',
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
            header: t('Date'),
            dataIndex: 'day',
            renderer: Ext.util.Format.dateRenderer('Y-m-d'),
            flex: 5
        }]
        me.callParent(arguments);
    }
});