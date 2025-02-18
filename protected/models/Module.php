<?php
/**
 * Modelo para a tabela "Module".
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
 * 19/09/2012
 */
/**
 * Model to table "module".
 *
 * Columns of table 'module':
 * @property integer $id.
 * @property string $text.
 * @property string $controller.
 * @property string $icon_cls.
 * @property integer $id_module.
 *
 * Relations of model:
 * @property GroupUser[] $groupUsers.
 * @property GroupModule[] $groupModules.
 * @property Module $idModule.
 * @property Module[] $modules.
 *
 * MagnusBilling <info@magnusbilling.com>
 * 15/04/2013
 */

class Module extends Model
{
    protected $_module = 'module';
    /**
     * Return the static class of model.
     * @return Module classe estatica da model.
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return name of table.
     */
    public function tableName()
    {
        return 'pkg_module';
    }

    /**
     * @return name of primary key(s).
     */
    public function primaryKey()
    {
        return 'id';
    }

    /**
     * @return array validation of fields of model.
     */
    public function rules()
    {
        return array(
            array('text', 'required'),
            array('id_module,priority', 'numerical', 'integerOnly' => true),
            array('text, icon_cls', 'length', 'max' => 100),
        );
    }

    /**
     * @return array roles of relationship.
     */
    public function relations()
    {
        return array(
            'groupUsers'   => array(self::MANY_MANY, 'GroupUser', 'group_module(id_module, id_group)'),
            'groupModules' => array(self::HAS_MANY, 'GroupModule', 'id_module'),
            'idModule'     => array(self::BELONGS_TO, 'Module', 'id_module'),
            'modules'      => array(self::HAS_MANY, 'Module', 'id_module'),
            'idUserType'   => array(self::BELONGS_TO, 'UserType', 'id_user_type'),
        );
    }
}
