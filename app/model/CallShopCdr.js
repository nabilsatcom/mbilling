/**
 * Classe que define a model "CallShopCdr"
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
 * 19/09/2012
 */
Ext.define('MBilling.model.CallShopCdr', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'status',
        type: 'int'
    }, {
        name: 'price',
        type: 'number'
    }, {
        name: 'buycost',
        type: 'number'
    }, {
        name: 'calledstation',
        type: 'number'
    }, {
        name: 'destination',
        type: 'string'
    }, {
        name: 'date',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
    }, {
        name: 'sessiontime',
        type: 'int'
    }, {
        name: 'cabina',
        type: 'string'
    }, {
        name: 'priceSum',
        type: 'number'
    }, {
        name: 'markup',
        type: 'number'
    }],
    proxy: {
        type: 'uxproxy',
        module: 'callShopCdr'
    }
});