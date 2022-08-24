/**
 * Classe que define a window import csv de "restrictedCallerrange"
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
Ext.define('MBilling.view.restrictedCallerrange.ImportCsv', {
    extend: 'Ext.ux.window.ImportCsv',
    alias: 'widget.restrictedcallerrangeimportcsv',
    htmlTipInfo: '<br>number<br>',
    fieldsImport: [{
        xtype: 'usercombo',
        width: 350
    }]
});