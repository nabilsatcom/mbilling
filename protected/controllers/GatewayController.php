<?php
/**
 * Acoes do modulo "Gateway".
 */

class GatewayController extends Controller
{
    public $extraValues    = array('idProvider' => 'provider_name', 'failoverGateway' => 'gatewaycode');
    public $nameFkRelated  = 'failover_gateway';
    public $attributeOrder = 'id';
    public $fieldsFkReport = array(
        'id_provider'    => array(
            'table'       => 'pkg_provider',
            'pk'          => 'id',
            'fieldReport' => 'provider_name',
        ),
        'failover_gateway' => array(
            'table'       => 'pkg_gateway',
            'pk'          => 'id',
            'fieldReport' => 'gatewaycode',
        ),
    );
    public function init()
    {
        $this->instanceModel = new Gateway;
        $this->abstractModel = Gateway::model();
        $this->titleReport   = Yii::t('zii', 'Gateway');

        parent::init();
    }

    public function beforeSave($values)
    {

        if ($this->isNewRecord) {
            if (isset($values['fromuser']) && strlen($values['fromuser']) == 0) {
                $values['fromuser'] = $values['user'];
            }

        }

        if ((isset($values['register']) && $values['register'] == 1 && isset($values['register_string']))
            && !preg_match("/^.{3}.*:.{3}.*@.{5}.*\/.{3}.*/", $values['register_string'])) {
            echo json_encode(array(
                'success' => false,
                'rows'    => array(),
                'errors'  => [
                    'register'        => Yii::t('zii', 'Invalid register string. Only use register option to Gateway authentication via user and password.'),
                    'register_string' => Yii::t('zii', 'Invalid register string'),
                ],
            ));
            exit();
        }

        if (isset($values['providerip'])) {
            $modelGateway = Gateway::model()->find((int) $values['id']);
            if (isset($values['providertech']) && $values['providertech'] != 'sip' && $values['providertech'] != 'iax2') {
                $values['providerip'] = $modelGateway->host;
            }

        }

        if (isset($values['failover_gateway'])) {
            $values['failover_gateway'] = $values['failover_gateway'] === 0 ? null : $values['failover_gateway'];
        }

        if (isset($values['gatewaycode'])) {
            $values['gatewaycode'] = preg_replace("/ /", "-", $values['gatewaycode']);
        }

        if (isset($values['allow'])) {
            $values['allow'] = preg_replace("/,0/", "", $values['allow']);
            $values['allow'] = preg_replace("/0,/", "", $values['allow']);
        }

        if (isset($values['status'])) {
            if ($values['status'] == 1) {
                $values['short_time_call'] = 0;
            }
        }

        return $values;
    }
    public function setAttributesModels($attributes, $models)
    {

        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            return $attributes;
        }

        $gatewayRegister = AsteriskAccess::instance()->sipShowRegistry();
        $gatewayRegister = explode("\n", $gatewayRegister['data']);

        $pkCount = is_array($attributes) || is_object($attributes) ? $attributes : [];
        for ($i = 0; $i < count($pkCount); $i++) {
            $modelGateway                                = Gateway::model()->findByPk((int) $attributes[$i]['failover_gateway']);
            $attributes[$i]['failover_gatewaygatewaycode'] = count($modelGateway)
            ? $modelGateway->gatewaycode
            : Yii::t('zii', 'Undefined');
            foreach ($gatewayRegister as $key => $gateway) {
                if (preg_match("/" . $attributes[$i]['host'] . ".*" . $attributes[$i]['username'] . ".*Registered/", $gateway) && $attributes[$i]['providertech'] == 'sip') {
                    $attributes[$i]['registered'] = 1;
                    break;
                }
            }

        }

        return $attributes;
    }

    //failover_gatewaygatewaycode

    public function generateSipFile()
    {

        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            return;
        }
        $select = 'gatewaycode, user, secret, disallow, allow, directmedia, context, dtmfmode, insecure, nat, qualify, type, host, fromdomain,fromuser, register_string,port,transport,encryption,sendrpid,maxuse,sip_config';
        $model  = Gateway::model()->findAll(
            array(
                'select'    => $select,
                'condition' => 'providertech = :key AND status = 1',
                'params'    => array(':key' => 'sip'),
            ));

        if (count($model)) {
            AsteriskAccess::instance()->writeAsteriskFile($model, '/etc/asterisk/sip_magnus_gateway.conf', 'gatewaycode');
        }

        $select = 'gatewaycode, user, secret, disallow, allow, directmedia, context, dtmfmode, insecure, nat, qualify, type, host, register_string,sip_config';
        $model  = Gateway::model()->findAll(
            array(
                'select'    => $select,
                'condition' => 'providertech = :key AND status = 1',
                'params'    => array(':key' => 'iax2'),
            ));

        if (count($model)) {
            AsteriskAccess::instance()->writeAsteriskFile($model, '/etc/asterisk/iax_magnus.conf', 'gatewaycode');
        }

    }

    public function afterUpdateAll($strIds)
    {
        $this->generateSipFile();
        return;
    }

    public function afterSave($model, $values)
    {
        $this->generateSipFile();
    }

    public function afterDestroy($values)
    {
        $this->generateSipFile();
    }
}
