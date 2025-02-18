/**
 * Classe que define o form de "Trunk"
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
 * 25/06/2012
 */
Ext.define('MBilling.view.trunkSipCodes.Form', {
    extend: 'Ext.ux.form.Panel',
    alias: 'widget.trunksipcodesform',
    initComponent: function() {
        var me = this;
        me.items = [{
            xtype: 'textfield',
            name: 'ip',
            fieldLabel: t('IP')
        }, {
            xtype: 'numberfield',
            name: 'code',
            fieldLabel: t('Code')
        }, {
            xtype: 'numberfield',
            name: 'total',
            fieldLabel: t('Total')
        }];
        me.callParent(arguments);
    }
});