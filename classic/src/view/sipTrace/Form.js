/**
 * Classe que define o form de "Call"
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
 * 19/09/2012
 */
Ext.define('MBilling.view.sipTrace.Form', {
    extend: 'Ext.ux.form.Panel',
    alias: 'widget.siptraceform',
    initComponent: function() {
        var me = this;
        me.allowCreate = false;
        height = Ext.Element.getViewportHeight() - 200;
        me.items = [{
            xtype: 'textareafield',
            name: 'head',
            fieldLabel: t(''),
            height: height,
            anchor: '100%',
            allowBlank: true,
            readOnly: true
        }];
        me.callParent(arguments);
    }
});