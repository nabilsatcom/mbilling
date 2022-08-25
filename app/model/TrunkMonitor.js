/**
 * Classe que define a model "TrunkMonitor"
 * =======================================
 * ###################################
 * 
 */
Ext.define('MBilling.model.TrunkMonitor', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'id_trunk',
        type: 'int'
    }, {
        name: 'id_trunk_group',
        type: 'int'
    }, 'idTrunktrunkcode','idTrunkGroupname', {
        name: 'starttime',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
    }, {
        name: 'OK',
        type: 'int'
    }, {
        name: 'NOK',
        type: 'int'
    }],
    proxy: {
        type: 'uxproxy',
        module: 'trunkMonitor'
    }
});