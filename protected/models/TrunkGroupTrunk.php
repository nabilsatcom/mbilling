<?php
/**
 * Modelo para a tabela "trunkGroupTrunk".
 * =======================================
 * ###################################
 *
 *
 * 
 */

class TrunkGroupTrunk extends Model
{
    protected $_module = 'trunkgrouptrunk';
    /**
     * Retorna a classe estatica da model.
     * @return SubModule classe estatica da model.
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
        return array('id', 'id_trunk_group', 'id_trunk');
    }

    /**
     * @return array validacao dos campos da model.
     */
    public function rules()
    {
        return array(
            array('id_trunk_group, id_trunk', 'required'),
            array('id_trunk_group, id_trunk', 'numerical', 'integerOnly' => true),
        );
    }

    /**
     * @return array regras de relacionamento.
     */
    public function relations()
    {
        return array(
            'idTrunk'      => array(self::BELONGS_TO, 'Trunk', 'id_trunk'),
            'idTrunkGroup' => array(self::BELONGS_TO, 'TrunkGroup', 'id_trunk_group'),
        );
    }
}
