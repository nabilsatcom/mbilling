/**
 * Classe que define a model "CallSummary"
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
 * 17/08/2012
 */
Ext.define('MBilling.model.CallSummaryPerUser', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'starttime',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
    }, {
        name: 'id_user',
        type: 'int'
    }, {
        name: 'aloc_all_calls',
        type: 'int'
    }, {
        name: 'sessiontime',
        type: 'int'
    }, {
        name: 'sessionbill',
        type: 'float'
    }, {
        name: 'buycost',
        type: 'float'
    }, {
        name: 'agent_bill',
        type: 'float'
    }, {
        name: 'agent_bill',
        type: 'float'
    }, {
        name: 'nbcall',
        type: 'int'
    }, {
        name: 'asr',
        type: 'float'
    }, {
        name: 'nbcall_fail',
        type: 'int'
    }, {
        name: 'lucro',
        type: 'float'
    }, {
        name: 'idUserusername',
        type: 'string'
    }, 'sumsessiontime', 'sumbuycost', 'sumlucro', 'sumsessionbill', 'sumaloc_all_calls', 'sumnbcall', 'sumasr'],
    proxy: {
        type: 'uxproxy',
        module: 'callSummaryPerUser'
    }
});