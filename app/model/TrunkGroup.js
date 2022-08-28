/**
 * Classe que define a model "trunkGroup"
 *
 * =======================================
 * ###################################
 *
 *
 */
Ext.define('MBilling.model.TrunkGroup', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'name',
        type: 'string'
    }, {
        name: 'type',
        type: 'int'
    }, {
        name: 'limit_ok',
        type: 'int'
    }, {
        name: 'limit_nok',
        type: 'int'
    }, {
        name: 'description',
        type: 'string'
    }, 'subRecords', 'id_trunk'],
    proxy: {
        type: 'uxproxy',
        module: 'trunkGroup'
    }
});