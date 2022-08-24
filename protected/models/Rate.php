<?php
/**
 * Modelo para a tabela "Rate".
 * =======================================
 * ###################################
 * 
 *
 */

class Rate extends Model
{
    protected $_module = 'rate';
    /**
     * Retorna a classe estatica da model.
     * @return Rate classe estatica da model.
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
        return 'pkg_rate';
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
            array('id_plan', 'required'),
            array('id_plan, id_prefix, id_trunk_history, History_Number, id_trunk_group, id_trunk_group2, id_trunk_group3,WL1,WL2,WL3,2min,att2min,5min,att5min,15min,att15min,1h,att1h,4h,att4h,1d,att1d, initblock, billingblock, package_offer, minimal_time_charge ', 'numerical', 'integerOnly' => true),
            array('rateinitial,connectcharge,disconnectcharge, additional_grace,status', 'length', 'max' => 15),
            array('FRCalled,FRCaller,FNCalled,FNCaller', 'length', 'max' => 100),
        );

    }
    /**
     * @return array regras de relacionamento.
     */
    public function relations()
    {

        return array(
            'idTrunkGroup' => array(self::BELONGS_TO, 'TrunkGroup', 'id_trunk_group'),
            'idTrunkGroup2' => array(self::BELONGS_TO, 'TrunkGroup', 'id_trunk_group2'),
            'idTrunkGroup3' => array(self::BELONGS_TO, 'TrunkGroup', 'id_trunk_group3'),
            'idPlan'       => array(self::BELONGS_TO, 'Plan', 'id_plan'),
            'idPrefix'     => array(self::BELONGS_TO, 'Prefix', 'id_prefix'),
        );

    }

    public function insertPortabilidadeRates($rates)
    {
        if (count($rates) > 0) {
            $sql = 'INSERT INTO pkg_rate (id_prefix, id_plan, rateinitial,  id_trunk_history, History_Number, id_trunk_group, id_trunk_group2, id_trunk_group3, WL1,WL2,WL3,FRCalled, FRCaller, FNCalled, FNCaller,2min,att2min,5min,att5min,15min,att15min,1h,att1h,4h,att4h,1d,att1d, initblock, billingblock,  status) VALUES ' . implode(',', $rates) . ';';
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    public function searchAgentRate($calledstation, $id_plan_agent)
    {
        $sql = "SELECT rateinitial, initblock, billingblock, minimal_time_charge " .
            "FROM pkg_plan " .
            "LEFT JOIN pkg_rate_agent ON pkg_rate_agent.id_plan=pkg_plan.id " .
            "LEFT JOIN pkg_prefix ON pkg_rate_agent.id_prefix=pkg_prefix.id " .
            "WHERE prefix = SUBSTRING(:calledstation,1,length(prefix)) and " .
            "pkg_plan.id= :id_plan_agent ORDER BY LENGTH(prefix) DESC ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":id_plan_agent", $id_plan_agent, PDO::PARAM_INT);
        $command->bindValue(":calledstation", $calledstation, PDO::PARAM_STR);
        return $command->queryAll();
    }

    public function insertRates($userType, $sqlRate)
    {

        if ($userType == 1) {
            $sqlRate = 'INSERT INTO pkg_rate (id_prefix, id_plan, rateinitial, id_trunk_history, History_Number, id_trunk_group, id_trunk_group2, id_trunk_group3,WL1,WL2,WL3,FRCalled,FRCaller,FNCalled,FNCaller,2min,att2min,5min,att5min,15min,att15min,1h,att1h,4h,att4h,1d,att1d, initblock, billingblock, status) VALUES ' . implode(',', $sqlRate) . ';';
        } else {
            $sqlRate = 'INSERT INTO pkg_rate_agent (id_prefix, id_plan, rateinitial,  initblock, billingblock) VALUES ' . implode(',', $sqlRate) . ';';
        }

        try {
            return Yii::app()->db->createCommand($sqlRate)->execute();
        } catch (Exception $e) {
            return $e;
        }
    }
}
