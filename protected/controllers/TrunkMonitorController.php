<?php
/**
 * Acoes do modulo "TrunkMonitor".
 * =======================================
 * ###################################
 * 
 * 
 * 
 */

class TrunkMonitorController extends Controller
{

    public $attributeOrder = 't.starttime DESC';
    public $nameFkRelated      = 'id_trunk_group';
    public $extraValues    = array(
        'idTrunk'    => 'trunkcode',
        'idTrunkGroup' => 'name',
    );

    public function init()
    {
        $this->instanceModel = new TrunkMonitor;
        $this->abstractModel = TrunkMonitor::model();
        $this->titleReport   = Yii::t('zii', 'Trunk Monitor');

        parent::init();
    }

}
