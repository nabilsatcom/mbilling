<?php
/**
 * Acoes do modulo "trunkGroup".
 *
 * =======================================
 * ###################################
 *
 *
 */

class TrunkGroupController extends Controller
{
    public $attributeOrder     = 't.id DESC';
    public $nameModelRelated   = 'TrunkGroupTrunk';
    public $nameFkRelated      = 'id_trunk_group';
    public $nameOtherFkRelated = 'id_trunk';

    public function init()
    {
        $this->instanceModel        = new TrunkGroup;
        $this->abstractModel        = TrunkGroup::model();
        $this->abstractModelRelated = TrunkGroupTrunk::model();
        $this->titleReport          = Yii::t('zii', 'Trunk Groups');

        parent::init();
    }
    public function saveUpdateAll($ids, $values, $module, $namePk, $subRecords)
    {
        if (Yii::app()->session['isClient']) {
            # retorna o resultado da execucao
            echo json_encode(array(
                $this->nameSuccess => false,
                $this->nameMsg     => 'Only admins can batch update this menu',
            ));
            exit;
        }

        $values = $this->getAttributesRequest();

        $criteria = new CDbCriteria();
        $criteria->addInCondition($this->nameFkRelated, $ids);
        $this->success = $this->abstractModelRelated->deleteAll($criteria);

        foreach ($ids as $key => $id_trunk_group) {
            foreach ($values['id_trunk'] as $key => $value) {

                $model                 = new TrunkGroupTrunk;
                $model->id_trunk_group = $id_trunk_group;
                $model->id_trunk       = $value;

                $id_trunk_group . ' ' . $value;
                $this->success = $model->save();
            }

        }

        echo json_encode(array(
            $this->nameSuccess => $this->success,
            $this->nameMsg     => 'Success',
        ));
        exit;

    }

}
