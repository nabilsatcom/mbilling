/**
 * Classe que define a model "RestrictedCallerrange"
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
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 * 13/10/2012
 */
Ext.define('MBilling.model.RestrictedCallerrange', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'id_user',
        type: 'int'
    }, {
        name: 'starttime',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
    }, {
        name: 'direction',
        type: 'int'
    }, {
        name: 'number',
        type: 'string'
    }, 'idUserusername'],
    proxy: {
        type: 'uxproxy',
        module: 'restrictedCallerrange'
    }
});