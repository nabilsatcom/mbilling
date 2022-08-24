/**
 * Class to define lookup of "user"
 *
 * 
 */
Ext.define('MBilling.view.trunk.Lookup', {
    extend: 'Ext.ux.form.field.Lookup',
    alias: 'widget.trunklookup',
    name: 'id_trunk',
    fieldLabel: t('Trunk'),
    displayField: 'idTrunktrunkcode',
    displayFieldList: 'trunkcode',
    gridConfig: {
        xtype: 'trunklist',
        fieldSearch: 'trunkcode',
        columns: [{
            header: t('Name'),
            dataIndex: 'trunkcode'
        }]
    }
});