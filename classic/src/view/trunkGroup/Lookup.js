/**
 * Class to define lookup of "user"
 *
 * Adilson L. Magnus <info@magnussolution.com> 
 * 05/09/2017
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
Ext.define('MBilling.view.trunkGroup.Lookup2', {
    extend: 'Ext.ux.form.field.Lookup',
    alias: 'widget.trunkgroup2lookup',
    name: 'id_trunk_group2',
    fieldLabel: t('Trunk groups 2'),
    displayField: 'idTrunkGroup2name',
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
Ext.define('MBilling.view.trunkGroup.Lookup3', {
    extend: 'Ext.ux.form.field.Lookup',
    alias: 'widget.trunkgroup3lookup',
    name: 'id_trunk_group3',
    fieldLabel: t('Trunk groups 3'),
    displayField: 'idTrunkGroup3name',
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