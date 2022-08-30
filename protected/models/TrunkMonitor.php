<?php
/**
 * Modelo para a tabela "TrunkMonitor".
 * =======================================
 * ###################################
 * 
 * 
 */
class TrunkMonitor extends Model
{
    protected $_module = 'trunkmonitor';

    /**
     * Retorna a classe estatica da model.
     * @return Trunk classe estatica da model.
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
        return 'pkg_trunk_group_trunk';
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
            array('id_trunk_group, id_trunk ', 'required'),
            array('id_trunk_group, id_trunk, status', 'numerical', 'integerOnly' => true),
            array('iccid', 'length', 'max' => 80),

            array('starttime, OK, NOK', 'length', 'max' => 50),




        );
    }
    public function relations()
    {
        return array(
            'idTrunk'      => array(self::BELONGS_TO, 'Trunk', 'id_trunk'),
            'idTrunkGroup' => array(self::BELONGS_TO, 'TrunkGroup', 'id_trunk_group'),
        );
    }


}
