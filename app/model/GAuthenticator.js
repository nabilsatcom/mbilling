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
Ext.define('MBilling.model.GAuthenticator', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'googleAuthenticator_enable',
        type: 'int'
    }, {
        name: 'google_authenticator_key',
        type: 'string'
    }, {
        name: 'code',
        type: 'string'
    }],
    proxy: {
        type: 'uxproxy',
        module: 'gAuthenticator'
    }
});