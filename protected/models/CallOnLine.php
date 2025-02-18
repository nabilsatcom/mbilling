<?php
/**
 * Modelo para a tabela "CallOnLine".
 * =======================================
 * ###################################
 *
 *
 */

class CallOnLine extends Model
{
    protected $_module = 'callonline';
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
        return 'pkg_call_online';
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
            array('id_user', 'numerical', 'integerOnly' => true),
            array('canal, tronco, from_ip, sip_account', 'length', 'max' => 50),
            array('ndiscado, status, duration', 'length', 'max' => 16),
            array('codec, reinvite', 'length', 'max' => 5),
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

    public function insertCalls($sql)
    {

        $sql = 'INSERT INTO pkg_call_online VALUES ' . implode(',', $sql) . ';';
        try {
            return Yii::app()->db->createCommand($sql)->execute();
        } catch (Exception $e) {
            return $e;
        }
    }

}
