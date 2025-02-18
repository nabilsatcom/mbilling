/**
 * Classe que define a model "Callerid"
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
Ext.define('MBilling.model.SipTrace', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'date',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
    }, {
        name: 'traced_data',
        type: 'string'
    }, {
        name: 'method',
        type: 'string'
    }, {
        name: 'callid',
        type: 'string'
    }, {
        name: 'fromip',
        type: 'string'
    }, {
        name: 'toip',
        type: 'string'
    }, {
        name: 'direction',
        type: 'string'
    }, {
        name: 'head',
        type: 'string'
    }, {
        name: 'sipto',
        type: 'string'
    }],
    proxy: {
        type: 'uxproxy',
        module: 'sipTrace'
    }
});