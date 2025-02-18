/**
 * Class to define lookup of "trunkGroup"
 *
 */
Ext.define('MBilling.view.trunkGroup.Lookup', {
    extend: 'Ext.ux.form.field.Lookup',
    alias: 'widget.trunkgrouplookup',
    name: 'id_trunk_group',
    fieldLabel: t('Trunk groups 1'),
    displayField: 'idTrunkGroupname',
    displayFieldList: 'name',
    gridConfig: {
        xtype: 'trunkgrouplist',
        fieldSearch: 'name',
        columns: [{
            header: t('Name'),
            dataIndex: 'name'
        }]
    }
});
