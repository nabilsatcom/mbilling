/**
 * Classe que define a combo de "providercombo"
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
 * 04/07/2012
 */
Ext.define('MBilling.view.provider.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.providercombo',
    name: 'id_provider',
    fieldLabel: t('Provider'),
    forceSelection: true,
    editable: false,
    displayField: 'provider_name',
    valueField: 'id',
    initComponent: function() {
        var me = this;
        me.store = Ext.create('MBilling.store.Provider', {
            proxy: {
                type: 'uxproxy',
                module: 'provider',
                limitParam: undefined
            }
        });
        me.callParent(arguments);
    }
});