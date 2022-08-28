<?php
/**
 * Modelo para a tabela "trunkGroup".
 * =======================================
 * ###################################
 *
 *
 */

class TrunkGroup extends Model
{
    protected $_module = 'trunkgroup';
    public $id_trunk;
    /**
     * Retorna a classe estatica da model.
     * @return Provider classe estatica da model.
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
        return 'pkg_trunk_group';
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
            array('name', 'required'),
            array('type', 'numerical', 'integerOnly' => true),
            array('description, limit_ok, limit_nok', 'length', 'max' => 500),
        );
    }

}
