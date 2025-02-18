/**
 * Classe que define a model "Refillprovider"
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
 * 18/07/2012
 */
Ext.define('MBilling.model.Refillprovider', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'date',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
    }, {
        name: 'credit',
        type: 'int'
    }, {
        name: 'id_provider',
        type: 'int'
    }, 'description', 'idProviderprovider_name', {
        name: 'payment',
        type: 'int'
    }],
    proxy: {
        type: 'uxproxy',
        module: 'refillprovider'
    }
});