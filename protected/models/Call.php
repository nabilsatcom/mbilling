<?php
/**
 * Modelo para a tabela "Call".
 * =======================================
 * ###################################
 *
 *
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusSolution. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v3
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 * 17/08/2012
 */

class Call extends Model
{
    public $totalCall;
    protected $_module       = 'call';
    public $sessiontimeFixed = 0;
    public $sessionbillFixed;
    public $sessiontimeMobile = 0;
    public $sessionbillMobile;
    public $sessiontimeRest = 0;
    public $sessionbillRest;
    public $stoptime;
    public $sumbuycost;
    public $sumsessionbill;
    /**
     * Retorna a classe estatica da model.
     * @return Prefix classe estatica da model.
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return nome da tabela.
     */
    public function tableName()
    {
        return 'pkg_cdr';
    }

    /**
     * @return nome da(s) chave(s) primaria(s).
     */
    public function primaryKey()
    {
        return 'id';
    }

    /**
     * @return array validacao dos campos da model.
     */
    public function rules()
    {
        return array(
            array('id_user, id_plan, calledstation', 'required'),
            array('id_user, id_plan, id_campaign, id_server, id_trunk, sessiontime, real_sessiontime, sipiax', 'numerical', 'integerOnly' => true),
            array('uniqueid, starttime, src, calledstation,
                terminatecauseid, buycost, sessionbill,  agent_bill, callerid', 'length', 'max' => 50),
        );
    }

    public function relations()
    {
        return array(
            'idPrefix'   => array(self::BELONGS_TO, 'Prefix', 'id_prefix'),
            'idPlan'     => array(self::BELONGS_TO, 'Plan', 'id_plan'),
            'idTrunk'    => array(self::BELONGS_TO, 'Trunk', 'id_trunk'),
            'idUser'     => array(self::BELONGS_TO, 'User', 'id_user'),
            'idCampaign' => array(self::BELONGS_TO, 'Campaign', 'id_campaign'),
            'idServer'   => array(self::BELONGS_TO, 'Servers', 'id_server'),
        );
    }
}
