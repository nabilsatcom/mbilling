/**
 * Classe que define a model "CallOnline"
 *
 * =======================================
 * ###################################
 * 
 */
Ext.define('MBilling.model.CallOnLine', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'id_user',
        type: 'int'
    }, {
        name: 'uniqueid',
        type: 'string'
    }, {
        name: 'canal',
        type: 'string'
    }, {
        name: 'tronco',
        type: 'string'
    }, {
        name: 'ndiscado',
        type: 'string'
    }, {
        name: 'codec',
        type: 'string'
    }, {
        name: 'status',
        type: 'string'
    }, {
        name: 'duration',
        type: 'int'
    }, {
        name: 'reinvite',
        type: 'string'
    }, {
        name: 'from_ip',
        type: 'string'
    }, {
        name: 'server',
        type: 'string'
    }, {
        name: 'sip_account',
        type: 'string'
    }, {
        name: 'callerid',
        type: 'string'
    }, 'idUserusername', 'idUsercredit'],
    proxy: {
        type: 'uxproxy',
        module: 'callOnLine'
    }
});