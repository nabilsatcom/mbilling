/**
 * Classe que define a window import csv de "restrictedCallednumber"
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
Ext.define('MBilling.view.restrictedCallednumber.ImportCsv', {
    extend: 'Ext.ux.window.ImportCsv',
    alias: 'widget.restrictedcallednumberimportcsv',
    htmlTipInfo: '<br>number<br>',
    fieldsImport: [{
        xtype: 'usercombo',
        width: 350
    }]
});