/*
 * Classe que define a lista de "servicesUse"
 *
 * =======================================
 * ###################################
 *
 *
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusBilling. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v3
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnussolution/magnusbilling7/issues
 * =======================================
 * Magnusbilling.org <info@magnussolution.com>
 * 24/09/2017
 */
Ext.define('MBilling.view.servicesUse.Module', {
    extend: 'Ext.ux.panel.Module',
    alias: 'widget.servicesusemodule',
    controller: 'servicesuse',
    cfgEast: {
        flex: 9
    }
});