/**
 * Classe que define a combo de "trunkGroup"
 *
 * =======================================
 * ###################################
 *
 *
 */
Ext.define('MBilling.view.trunkGroup.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.trunkgroupcombo',
    name: 'id_trunk_group',
    fieldLabel: t('Trunk groups'),
    forceSelection: true,
    editable: false,
    displayField: 'name',
    valueField: 'id',
    initComponent: function() {
        var me = this;
        me.store = Ext.create('MBilling.store.TrunkGroup', {
            proxy: {
                type: 'uxproxy',
                module: 'trunkGroup',
                limitParam: undefined
            }
        });
        me.callParent(arguments);
    }
});