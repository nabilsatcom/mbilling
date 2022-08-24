/**
 * Classe que define a window import csv de "restrictedCallernumber"
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author  Adilson Leffa Magnus.
 * @copyright   Todos os direitos reservados.
 * ###################################
 * =======================================
 * MagnusSolution.com <info@magnussolution.com>
 * 08/11/2012
 */
Ext.define('MBilling.view.restrictedCallernumber.ImportCsv', {
    extend: 'Ext.ux.window.ImportCsv',
    alias: 'widget.restrictedcallernumberimportcsv',
    htmlTipInfo: '<br>number<br>',
    fieldsImport: [{
        xtype: 'usercombo',
        width: 350
    }]
});