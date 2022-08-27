/**
 * Class to window about
 *
 * Adilson L. Magnus <info@magnussolution.com>
 * 12/12/2012
 */
Ext.define('MBilling.view.main.About', {
    extend: 'Ext.window.Window',
    alias: 'widget.about',
    title: t('About'),
    resizable: false,
    autoShow: true,
    initComponent: function() {
        var me = this;
        me.callParent(arguments);
    }
});