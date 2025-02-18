/**
 * Classe que define a model "Provider"
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
 * 16/07/2012
 */
Ext.define('MBilling.model.Provider', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'provider_name',
        type: 'string'
    }, {
        name: 'creationdate',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
    }, {
        name: 'description',
        type: 'string'
    }, {
        name: 'credit_control',
        type: 'int'
    }, {
        name: 'credit',
        type: 'number'
    }],
    proxy: {
        type: 'uxproxy',
        module: 'provider'
    }
});