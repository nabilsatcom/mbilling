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

class CallSummaryDayUser extends Model
{
    protected $_module = 'callsummarydayuser';

    public $sumsessiontime;
    public $sumsessionbill;
    public $sumagent_bill;
    public $sumbuycost;
    public $sumlucro;
    public $sumaloc_all_calls;
    public $sumnbcall;
    public $sumasr;

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
        return 'pkg_cdr_summary_day_user';
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
            array('sessiontime,day, id_user, sessionbill, nbcall, buycost, lucro, aloc_all_calls, sumaloc_all_calls, nbcall_fail, asr', 'length', 'max' => 50),
        );
    }

    /**
     * @return array regras de relacionamento.
     */
    public function relations()
    {
        return array(
            'idUser' => array(self::BELONGS_TO, 'User', 'id_user'),
        );
    }

}
