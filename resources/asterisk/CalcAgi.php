<?php
/**
 * =======================================
 * ###################################
 *
 *
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusSolution. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 *
 */
class CalcAgi
{
    public $lastcost                  = 0;
    public $lastbuycost               = 0;
    public $buycost                   = 0;
    public $answeredtime              = 0;
    public $real_answeredtime         = 0;
    public $dialstatus                = 0;
    public $usedratecard              = 0;
    public $usedtrunk                 = 0;
    public $freetimetocall_used       = 0;
    public $number_trunk              = 0;
    public $idCallCallBack            = 0;
    public $agent_bill                = 0;
    public $did_charge_of_id_user     = 0;
    public $did_charge_of_answer_time = 0;
    public $starttime                 = 0;
    public $sessiontime               = 0;
    public $real_sessiontime          = 0;
    public $terminatecauseid          = 0;
    public $sessionbill               = 0;
    public $sipiax                    = 0;
    public $id_campaign               = '';
    public $tariffObj                 = array();
    public $freetimetocall_left       = array();
    public $freecall                  = array();
    public $offerToApply              = array();
    public $didAgi                    = array();
    public $dialstatus_rev_list;
    public $id_prefix;
    public $id_provider;

    public function __construct()
    {
        $this->dialstatus_rev_list = Magnus::getDialStatus_Revert_List();
    }

    public function init()
    {
        $this->number_trunk      = 0;
        $this->answeredtime      = 0;
        $this->real_answeredtime = 0;
        $this->dialstatus        = '';
        $this->usedtrunk         = '';
        $this->lastcost          = '';
        $this->lastbuycost       = '';
    }

    public function calculateAllTimeout(&$MAGNUS, $agi)
    {
        if (!is_array($this->tariffObj) || count($this->tariffObj) == 0) {
            return false;
        }

        $res_calcultimeout = $this->calculateTimeout($MAGNUS, $agi);

        if (substr($res_calcultimeout, 0, 5) == 'ERROR' || $res_calcultimeout < 1) {
            return false;
        } else {
            return true;
        }

        return true;
    }

    public function calculateTimeout(&$MAGNUS, $agi)
    {
        $rateinitial                  = $MAGNUS->round_precision(abs($this->tariffObj[0]['rateinitial']));
        $initblock                    = $this->tariffObj[0]['initblock'];
        $billingblock                 = $this->tariffObj[0]['billingblock'];
        $connectcharge                = $MAGNUS->round_precision(abs($this->tariffObj[0]['connectcharge']));
        $id_offer                     = $package_offer                     = $this->tariffObj[0]['package_offer'];
        $id_rate                      = $this->tariffObj[0]['id_rate'];
        $initial_credit               = $credit               = $MAGNUS->credit;
        $this->freetimetocall_left[0] = 0;
        $this->freecall[0]            = false;
        $this->offerToApply[0]        = null;

        if ($id_offer == 1 && $MAGNUS->id_offer > 0) {
            $sql = "SELECT * FROM pkg_offer_use WHERE id_offer = $MAGNUS->id_offer
                                AND id_user = $MAGNUS->id_user AND status = 1 AND releasedate = '0000-00-00 00:00:00' LIMIT 1";
            $modelOfferUse = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            if (isset($modelOfferUse->id)) {
                $sql        = "SELECT * FROM pkg_offer WHERE id = $MAGNUS->id_offer LIMIT 1";
                $modelOffer = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

                $package_selected = false;
                $freetimetocall   = $modelOffer->freetimetocall;
                $packagetype      = $modelOffer->packagetype;
                $billingtype      = $modelOffer->billingtype;
                $startday         = date('d', strtotime($modelOfferUse->reservationdate));
                $id_offer         = $modelOffer->id;
                switch ($packagetype) {
                    case 0:
                        $agi->verbose("offer Unlimited calls");
                        $this->freecall[0]     = true;
                        $package_selected      = true;
                        $this->offerToApply[0] = array(
                            "id"                  => $id_offer,
                            "label"               => "Unlimited calls",
                            "type"                => $packagetype,
                            "billingblock"        => $modelOffer->billingblock,
                            "initblock"           => $modelOffer->initblock,
                            "minimal_time_charge" => $modelOffer->minimal_time_charge,
                        );
                        break;
                    case 1:

                        if ($freetimetocall > 0) {
                            $agi->verbose('FREE CALLS');
                            $number_calls_used = $MAGNUS->freeCallUsed($agi, $MAGNUS->id_user, $id_offer, $billingtype, $startday);

                            if ($number_calls_used < $freetimetocall) {
                                $this->freecall[0]     = true;
                                $package_selected      = true;
                                $this->offerToApply[0] = array(
                                    "id"                  => $id_offer,
                                    "label"               => "Number of Free calls",
                                    "type"                => $packagetype,
                                    "billingblock"        => $modelOffer->billingblock,
                                    "initblock"           => $modelOffer->initblock,
                                    "minimal_time_charge" => $modelOffer->minimal_time_charge,
                                );
                                $agi->verbose(print_r($this->offerToApply[0], true), 6);
                            }
                        }
                        break;
                    case 2:
                        if ($freetimetocall > 0) {
                            $this->freetimetocall_used    = $MAGNUS->packageUsedSeconds($agi, $MAGNUS->id_user, $id_offer, $billingtype, $startday);
                            $this->freetimetocall_left[0] = $freetimetocall - $this->freetimetocall_used;

                            if ($this->freetimetocall_left[0] < 0) {
                                $this->freetimetocall_left[0] = 0;
                            }

                            if ($this->freetimetocall_left[0] > 0) {
                                $package_selected      = true;
                                $this->offerToApply[0] = array(
                                    "id"                  => $id_offer,
                                    "label"               => "Free minutes",
                                    "type"                => $packagetype,
                                    "billingblock"        => $modelOffer->billingblock,
                                    "initblock"           => $modelOffer->initblock,
                                    "minimal_time_charge" => $modelOffer->minimal_time_charge,
                                );
                                $agi->verbose(print_r($this->offerToApply[0], true), 6);
                            }
                        }
                        break;
                }
            }
        }

        $credit -= $connectcharge;
        $this->tariffObj[0]['timeout']                     = 0;
        $this->tariffObj[0]['timeout_without_rules']       = 0;
        $this->tariffObj[0]['freetime_include_in_timeout'] = $this->freetimetocall_left[0];
        $agi->verbose("Credit $credit", 20);
        if ($credit < 0 && !$this->freecall[0] && $this->freetimetocall_left[0] <= 0) {
            return "ERROR CT1";
            /*NO  CREDIT TO CALL */
        }

        $TIMEOUT              = 0;
        $answeredtime_1st_leg = 0;
        if ($rateinitial <= 0) /*Se o preÃ§o for 0, entao retornar o timeout em 3600 s*/ {
            $this->tariffObj[0]['timeout']               = 3600;
            $this->tariffObj[0]['timeout_without_rules'] = 3600;
            $TIMEOUT                                     = 3600;
            return $TIMEOUT;
        }

        if ($this->freecall[0]) /*usado para planos gratis*/ {
            $this->tariffObj[0]['timeout']                     = 3600;
            $TIMEOUT                                           = 3600;
            $this->tariffObj[0]['timeout_without_rules']       = 3600;
            $this->tariffObj[0]['freetime_include_in_timeout'] = 3600;
            return $TIMEOUT;
        }
        if ($credit < 0 && $this->freetimetocall_left[0] > 0) {
            $this->tariffObj[0]['timeout']               = $this->freetimetocall_left[0];
            $TIMEOUT                                     = $this->freetimetocall_left[0];
            $this->tariffObj[0]['timeout_without_rules'] = $this->freetimetocall_left[0];
            return $TIMEOUT;
        }

        if ($MAGNUS->mode == 'callback') {
            $credit -= $calling_party_connectcharge;
            $credit -= $calling_party_disconnectcharge;
            $num_min              = $credit / ($rateinitial + $calling_party_rateinitial);
            $answeredtime_1st_leg = intval($agi->get_variable('ANSWEREDTIME', true));
        } else {
            $num_min = $credit / $rateinitial; /*numero de minutos*/
        }

        $num_sec = intval($num_min * 60) - $answeredtime_1st_leg; /*numero de segundos - o tempo que gastou para completar*/
        if ($billingblock > 0) {
            $mod_sec = $num_sec % $billingblock;
            $num_sec = $num_sec - $mod_sec;
        }
        $TIMEOUT = $num_sec;

        /*Call time to speak without rate rules... idiot rules*/
        $num_min_WR                                  = $initial_credit / $rateinitial;
        $num_sec_WR                                  = intval($num_min_WR * 60);
        $this->tariffObj[0]['timeout_without_rules'] = $num_sec_WR + $this->freetimetocall_left[0];
        $this->tariffObj[0]['timeout']               = $TIMEOUT + $this->freetimetocall_left[0];

        return $TIMEOUT + $this->freetimetocall_left[0];
    }

    public function calculateCost(&$MAGNUS, $callduration, $agi)
    {
        $rateinitial           = $MAGNUS->round_precision(abs($this->tariffObj[0]['rateinitial']));
        $initblock             = $this->tariffObj[0]['initblock'];
        $billingblock          = $this->tariffObj[0]['billingblock'];
        $connectcharge         = $MAGNUS->round_precision(abs($this->tariffObj[0]['connectcharge']));
        $disconnectcharge      = $MAGNUS->round_precision(abs($this->tariffObj[0]['disconnectcharge']));
        $additional_grace_time = $this->tariffObj[0]['additional_grace'];

        $this->freetimetocall_used = 0;

        $cost = 0;
        $cost += $connectcharge;

        if ($this->freecall[0]) {
            $this->lastcost = 0;
            $agi->verbose("CALCUL COST: SELLING COST: $cost", 10);
            return;
        }
        if ($callduration < $initblock) {
            $callduration = $initblock;
        }

        if (($billingblock > 0) && ($callduration > $initblock)) {
            $mod_sec = $callduration % $billingblock;
            if ($mod_sec > 0) {
                $callduration += ($billingblock - $mod_sec);
            }
        }
        if ($this->freetimetocall_left[0] >= $callduration) {
            $this->freetimetocall_used = $callduration;
            $callduration              = 0;
        }
        $cost += ($callduration / 60) * $rateinitial;

        $agi->verbose("CALCULCOST: 1. COST: \$cost = ($callduration/60) * $rateinitial ", 10);

        $this->lastcost = $MAGNUS->round_precision($cost);
        $agi->verbose("CALCULCOST:  -  SELLING COST:$this->lastcost", 10);
    }

    public function array_csort()
    {
        $args      = func_get_args();
        $marray    = array_shift($args);
        $i         = 0;
        $msortline = "return(array_multisort(";
        foreach ($args as $arg) {
            $i++;
            if (is_string($arg)) {
                foreach ($marray as $row) {
                    $sortarr[$i][] = $row[$arg];
                }
            } else {
                $sortarr[$i] = $arg;
            }
            $msortline .= "\$sortarr[" . $i . "],";
        }
        $msortline .= "\$marray));";
        eval($msortline);
        return $marray;
    }

    public function updateSystem(&$MAGNUS, &$agi, $doibill = 1, $didcall = 0, $callback = 0)
    {
        $agi->verbose('Update System', 6);

        $id_offer              = $MAGNUS->id_offer;
        $additional_grace_time = $this->tariffObj[0]['additional_grace'];
        $sessiontime           = $this->answeredtime;
        $dialstatus            = $this->dialstatus;

        if ($sessiontime > 0) {
            $this->freetimetocall_used = 0;
            //adiciona o tempo adicional
            if (substr($additional_grace_time, -1) == "%") {
                $additional_grace_time = str_replace("%", "", $additional_grace_time);
                $additional_grace_time = $additional_grace_time / 100;
                $additional_grace_time = str_replace("0.", "1.", $additional_grace_time);
                $sessiontime           = $sessiontime * $additional_grace_time;
            } else {
                if ($sessiontime > 0) {
                    $sessiontime = $sessiontime + $additional_grace_time;
                }
            }

            if (($id_offer != -1) && ($this->offerToApply[0] != null)) {
                $id_offer = $this->offerToApply[0]["id"];

                $this->calculateCost($MAGNUS, $sessiontime, $agi);

                if ($sessiontime > $this->offerToApply[0]["minimal_time_charge"]) {

                    switch ($this->offerToApply[0]["type"]) {
                        /*Unlimited*/
                        case 0:
                            $this->freetimetocall_used = $sessiontime;
                            break;
                        /*free calls*/
                        case 1:
                            $this->freetimetocall_used = $sessiontime;
                            break;
                        /*free minutes*/
                        case 2:
                            if ($sessiontime > $this->offerToApply[0]["initblock"]) {
                                $restominutos   = $sessiontime % $this->offerToApply[0]["billingblock"];
                                $calculaminutos = ($sessiontime - $restominutos) / $this->offerToApply[0]["billingblock"];
                                if ($restominutos > '0') {
                                    $calculaminutos++;
                                }

                                $sessiontime = $calculaminutos * $this->offerToApply[0]["billingblock"];
                            } elseif ($sessiontime < '1') {
                                $sessiontime = 0;
                            } else {
                                $sessiontime = $this->offerToApply[0]["initblock"];
                            }

                            $this->freetimetocall_used = $sessiontime;

                            break;
                    }

                    /* calculcost could have change the duration of the call*/
                    $sessiontime = $this->answeredtime;
                    /* add grace time*/
                    $fields = "id_user, id_offer, used_secondes";
                    $values = "$MAGNUS->id_user, $id_offer, '$this->freetimetocall_used'";
                    $sql    = "INSERT INTO pkg_offer_cdr ($fields) VALUES ($values)";
                    $agi->exec($sql);
                } else {
                    $sessiontime = 0;
                }
            } else {
                $this->calculateCost($MAGNUS, $sessiontime, $agi);
            }
        } else {
            $sessiontime = 0;
        }
        $agi->verbose('Sessiontime' . $sessiontime, 10);

        if (($id_offer != -1) && ($this->offerToApply[0] != null) && $sessiontime > 0) {
            $sessiontime = $this->freetimetocall_used;
        }

        $id_prefix = $this->tariffObj[0]['id_prefix'];
        $id_plan   = $this->tariffObj[0]['id_plan'];

        if ($doibill == 0 || $sessiontime <= $this->tariffObj[0]['minimal_time_charge']) {
            $cost = 0;
        } else {
            $cost = $this->lastcost;
        }

        $agi->verbose("CALL: (sessiontime=$sessiontime :: dialstatus=$dialstatus)", 10);

        if ($didcall) {
            $calltype = 2;
        } elseif ($callback == 2) {
            $calltype = 7;
        } elseif ($callback) {
            $calltype = 4;
        } else {
            $calltype = 0;
        }

        if (strlen($this->dialstatus_rev_list[$dialstatus]) > 0) {
            $terminatecauseid = $this->dialstatus_rev_list[$dialstatus];
        } else {
            $terminatecauseid = 0;
        }

        if ($callback == 2) //muda o termino para transferencia
        {
            $terminatecauseid = 1;
        }

        if ($calltype == '4' && $sessiontime > '0') {
            $terminatecauseid = '1';
        }

        /*recondeo call*/
        if ($MAGNUS->config["global"]['bloc_time_call'] == 1 && $sessiontime > 0) {
            $initblock    = ($this->tariffObj[0]['initblock'] < 1) ? 1 : $this->tariffObj[0]['initblock'];
            $billingblock = ($this->tariffObj[0]['billingblock'] < 1) ? 1 : $this->tariffObj[0]['billingblock'];

            if ($sessiontime > $initblock) {
                $restominutos   = $sessiontime % $billingblock;
                $calculaminutos = ($sessiontime - $restominutos) / $billingblock;
                if ($restominutos > '0') {
                    $calculaminutos++;
                }

                $sessiontime = $calculaminutos * $billingblock;

            } elseif ($sessiontime < '1') {
                $sessiontime = 0;
            } else {
                $sessiontime = $initblock;
            }
        }

        $calldestinationPortabilidade = $MAGNUS->destination;
        if ($MAGNUS->portabilidade == 1) {
            if (substr($MAGNUS->destination, 0, 4) == '1111') {
                $MAGNUS->destination = str_replace(substr($MAGNUS->destination, 0, 7), "", $MAGNUS->destination);
            }

        }
        $cost += $MAGNUS->callingcardConnection;

        $agi->verbose($terminatecauseid . ' ' . $cost . '+' . $MAGNUS->round_precision(abs($MAGNUS->callingcardConnection)) . ' = ' . $cost, 25);
        $costCdr = $cost;
        if ($this->real_answeredtime > 0) {

            if ($this->usedtrunk > 0) {
                $sql = "SELECT * FROM pkg_rate_provider t  JOIN pkg_prefix p ON t.id_prefix = p.id WHERE " .
                "id_provider = " . $this->id_provider . " AND " . $MAGNUS->prefixclause .
                    "ORDER BY LENGTH( prefix ) DESC LIMIT 1";
                $modelRateProvider = $agi->query($sql)->fetchAll(PDO::FETCH_OBJ);

                $this->buycost = 0;

                if (isset($modelRateProvider[0]->id)) {
                    $buyrate             = $modelRateProvider[0]->buyrate;
                    $buyrateinitblock    = $modelRateProvider[0]->buyrateinitblock;
                    $buyrateincrement    = $modelRateProvider[0]->buyrateincrement;
                    $minimal_time_buy    = $modelRateProvider[0]->minimal_time_buy;
                    $buyratecallduration = $this->real_answeredtime;

                    $agi->verbose($this->real_answeredtime . ' ' . $buyrate . ' ' . $buyrateinitblock . ' ' . $buyrateincrement);

                    if ($this->real_answeredtime > $minimal_time_buy) {

                        if ($buyratecallduration < $buyrateinitblock) {
                            $buyratecallduration = $buyrateinitblock;
                        }

                        if (($buyrateincrement > 0) && ($buyratecallduration > $buyrateinitblock)) {
                            $mod_sec = $buyratecallduration % $buyrateincrement;
                            if ($mod_sec > 0) {
                                $buyratecallduration += ($buyrateincrement - $mod_sec);
                            }
                        }
                        $this->buycost += ($buyratecallduration / 60) * $buyrate;
                    }

                }
            }

            CallbackAgi::chargeFistCall($agi, $MAGNUS, $this, $sessiontime);
            /*Update the global credit */
            $MAGNUS->credit = $MAGNUS->credit - $cost;
            /*CALULATION CUSTO AND SELL RESELLER */

            if (!is_null($MAGNUS->id_agent) && $MAGNUS->id_agent > 1) {
                $agi->verbose('$MAGNUS->id_agent' . $MAGNUS->id_agent . ' ' . $MAGNUS->destination . ' - ' .
                    $calldestinationPortabilidade . ' - ' . $this->real_answeredtime . ' - ' . $cost, 1);
                $cost = $this->agent_bill = $this->updateSystemAgent($agi, $MAGNUS, $calldestinationPortabilidade, $MAGNUS->round_precision(abs($cost)), $sessiontime);
            }

        }
        $this->callShop($agi, $MAGNUS, $sessiontime, $id_prefix, $cost);

        if ($this->did_charge_of_id_user > 0) {

            $agi->verbose('Did_charge_of_id_user = ' . $this->did_charge_of_id_user, 15);

            $didDuration = time() - $this->did_charge_of_answer_time;

            $agi->verbose('DidDuration = ' . $didDuration);
            $did_sell_price = $this->didAgi->selling_rate_1;

            $did_sell_price = $MAGNUS->roudRatePrice($didDuration, $did_sell_price,
                $this->didAgi->initblock,
                $this->didAgi->increment);

            $agi->verbose('did_sell_price ' . $did_sell_price);

            $agi->verbose('Add CDR the DID cost to CallerID. Cost =' . $did_sell_price . ', duration' . $didDuration);

            $MAGNUS->id_user       = $this->did_charge_of_id_user;
            $MAGNUS->calledstation = $this->didAgi->did;
            $MAGNUS->id_plan       = $MAGNUS->id_plan;
            $MAGNUS->id_trunk      = null;

            $this->starttime        = date("Y-m-d H:i:s", $this->did_charge_of_answer_time);
            $this->sessiontime      = $didDuration;
            $this->real_sessiontime = $didDuration;
            $this->terminatecauseid = $terminatecauseid;
            $this->sessionbill      = $did_sell_price;
            $this->sipiax           = 3;
            $this->buycost          = 0;
            $this->id_prefix        = $id_prefix;
            $this->saveCDR($agi, $MAGNUS);

        }

        if ($terminatecauseid == 1) {
            if ($agi->get_variable("IDCALLBACK", true)) {
                $sql = "UPDATE pkg_callback SET last_attempt_time = '" . date('Y-m-d H:i:s') . "', status = 3
                            WHERE id = '" . $agi->get_variable("IDCALLBACK", true) . "' LIMIT 1";
                $agi->exec($sql);
            }
        }

        $MAGNUS->id_trunk       = $this->usedtrunk;
        $this->starttime        = date("Y-m-d H:i:s", time() - $this->real_answeredtime);
        $this->sessiontime      = intval($sessiontime);
        $this->real_sessiontime = intval($this->real_answeredtime);
        $this->terminatecauseid = $terminatecauseid;
        $this->sessionbill      = $costCdr;
        $this->sipiax           = $calltype;
        $this->id_prefix        = $id_prefix;
        $this->saveCDR($agi, $MAGNUS);

    }

    public function updateSystemAgent($agi, $MAGNUS, $calledstation, $cost, $sessiontime)
    {
        $sql = "SELECT rateinitial, initblock, billingblock, minimal_time_charge " .
            "FROM pkg_plan " .
            "LEFT JOIN pkg_rate_agent ON pkg_rate_agent.id_plan=pkg_plan.id " .
            "LEFT JOIN pkg_prefix ON pkg_rate_agent.id_prefix=pkg_prefix.id " .
            "WHERE prefix = SUBSTRING($calledstation,1,length(prefix)) and " .
            "pkg_plan.id= $MAGNUS->id_plan_agent ORDER BY LENGTH(prefix) DESC LIMIT 3";
        $modelRateAgent = $agi->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        if (!isset($modelRateAgent[0]['rateinitial'])) {
            $agi->verbose('NOT FOUND AGENT TARRIF, USE AGENT COST PRICE');
            $cost_customer = $cost;
        } else {
            $agi->verbose('Found agent sell price ' . print_r($modelRateAgent[0], true) . '-  ' . $sessiontime, 25);
            $cost_customer = $MAGNUS->roudRatePrice($sessiontime, $modelRateAgent[0]['rateinitial'],
                $modelRateAgent[0]['initblock'], $modelRateAgent[0]['billingblock']);
            $agi->verbose('$cost_customer=' . $cost_customer);
        }

        if ($sessiontime < $modelRateAgent[0]['minimal_time_charge']) {
            $agi->verbose("Tempo meno que o tempo minimo para", 15);
            $cost_customer = 0;
        }

        $agi->verbose("Update credit customer Agent $MAGNUS->username, " . $MAGNUS->round_precision(abs($cost_customer)), 6);

        return $cost_customer;
    }

    public function sendCall($agi, $destination, &$MAGNUS, $typecall = 0)
    {
        if (substr("$destination", 0, 4) == 1111) /*Retira o techprefix de numeros portados*/ {
            $destination = str_replace(substr($destination, 0, 7), "", $destination);
        }
        $old_destination = $destination;

                // #####################################
        // FRCalled
        // #####################################

        if ($this->tariffObj[0]['FRCalled'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT id FROM pkg_restrict_called_range WHERE  id_user = $MAGNUS->id_user AND number = SUBSTRING('" . $destination . "',1,length(number)) ORDER BY LENGTH(number) DESC";
            $modelRestrictedFRCalled = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS ", 15);

            if ($this->tariffObj[0]['FRCalled'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestrictedFRCalled->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS", 1);
                    $agi->execute((congestion), Congestion);
                    return 0;
                }
            }

        }

        // #####################################
        // FRCaller
        // #####################################

        if ($this->tariffObj[0]['FRCaller'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT id FROM pkg_restrict_caller_range WHERE id_user = $MAGNUS->id_user AND number = SUBSTRING('" . $MAGNUS->CallerID . "',1,length(number))   ORDER BY LENGTH(number) DESC";
            $modelRestrictedFRCaller = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS ", 15);

            if ($this->tariffObj[0]['FRCaller'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestrictedFRCaller->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS", 1);
                    $agi->execute((congestion), Congestion);
                    return 0;
                }
            }

        }

        // #####################################
        // FNCalled
        // #####################################

        if ($this->tariffObj[0]['FNCalled'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT id FROM pkg_restrict_called_number WHERE  id_user = $MAGNUS->id_user AND number = $destination ORDER BY number DESC";
            $modelRestrictedFNCalled = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS ", 15);

            if ($this->tariffObj[0]['FNCalled'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestrictedFNCalled->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS", 1);
                    $agi->execute((congestion), Congestion);
                    return 0;
                }
            }
        }

        // #####################################
        // FNCaller
        // #####################################
        
        if ($this->tariffObj[0]['FNCaller'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT id FROM pkg_restrict_caller_number WHERE id_user = $MAGNUS->id_user AND number = SUBSTRING('" . $MAGNUS->CallerID . "',1,length(number))   ORDER BY LENGTH(number) DESC";
            $modelRestrictedFNCaller = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS ", 15);

            if ($this->tariffObj[0]['FNCaller'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestrictedFNCaller->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS", 1);
                    $agi->execute((congestion), Congestion);
                    return 0;
                }
            }
        }

        // #####################################
        // 2 MIN
        // #####################################
        
        if ($this->tariffObj[0]['2min'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT * FROM ( SELECT pkg_cdr.id, pkg_cdr.calledstation, pkg_cdr.starttime FROM pkg_cdr WHERE pkg_cdr.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr.calledstation = '{$destination}' UNION ALL SELECT pkg_cdr_failed.id, pkg_cdr_failed.calledstation, pkg_cdr_failed.starttime FROM pkg_cdr_failed WHERE pkg_cdr_failed.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr_failed.calledstation = '{$destination}' AND pkg_cdr_failed.terminatecauseid != '5' AND pkg_cdr_failed.terminatecauseid != '6' ORDER BY starttime DESC ) AS TT GROUP BY calledstation ORDER BY starttime DESC";
            $modelRestricted2min = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS LAST 2 MIN ", 15);

            if ($this->tariffObj[0]['2min'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestricted2min->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS LAST 2 MIN", 1);
                    $agi->execute((congestion), Congestion);
                    return 0;
                }
            }
        }

        // #####################################
        // 5 MIN
        // #####################################
        
        if ($this->tariffObj[0]['5min'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT * FROM ( SELECT pkg_cdr.id, pkg_cdr.calledstation, pkg_cdr.starttime FROM pkg_cdr WHERE pkg_cdr.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr.calledstation = '{$destination}' UNION ALL SELECT pkg_cdr_failed.id, pkg_cdr_failed.calledstation, pkg_cdr_failed.starttime FROM pkg_cdr_failed WHERE pkg_cdr_failed.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr_failed.calledstation = '{$destination}' AND pkg_cdr_failed.terminatecauseid != '5' AND pkg_cdr_failed.terminatecauseid != '6' ORDER BY starttime DESC ) AS TT GROUP BY calledstation ORDER BY starttime DESC";
            $modelRestricted5min = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS LAST 5 MIN ", 15);

            if ($this->tariffObj[0]['5min'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestricted5min->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS LAST 5 MIN", 1);
                    $agi->execute((congestion), Congestion);
                    $agi->congestion($agi);
                }
            }
        }


        // #####################################
        // 15 MIN
        // #####################################
        
        if ($this->tariffObj[0]['15min'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT * FROM ( SELECT pkg_cdr.id, pkg_cdr.calledstation, pkg_cdr.starttime FROM pkg_cdr WHERE pkg_cdr.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr.calledstation = '{$destination}' UNION ALL SELECT pkg_cdr_failed.id, pkg_cdr_failed.calledstation, pkg_cdr_failed.starttime FROM pkg_cdr_failed WHERE pkg_cdr_failed.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr_failed.calledstation = '{$destination}' AND pkg_cdr_failed.terminatecauseid != '5' AND pkg_cdr_failed.terminatecauseid != '6' ORDER BY starttime DESC ) AS TT GROUP BY calledstation ORDER BY starttime DESC";
            $modelRestricted15min = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS LAST 15 MIN ", 15);

            if ($this->tariffObj[0]['15min'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestricted15min->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS LAST 15 MIN", 1);
                    $agi->execute((congestion), Congestion);
                    $agi->congestion($agi);
                }
            }
        }


        // #####################################
        // 1 HOUR
        // #####################################
        
        if ($this->tariffObj[0]['1h'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT * FROM ( SELECT pkg_cdr.id, pkg_cdr.calledstation, pkg_cdr.starttime FROM pkg_cdr WHERE pkg_cdr.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr.calledstation = '{$destination}' UNION ALL SELECT pkg_cdr_failed.id, pkg_cdr_failed.calledstation, pkg_cdr_failed.starttime FROM pkg_cdr_failed WHERE pkg_cdr_failed.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr_failed.calledstation = '{$destination}' AND pkg_cdr_failed.terminatecauseid != '5' AND pkg_cdr_failed.terminatecauseid != '6' ORDER BY starttime DESC ) AS TT GROUP BY calledstation ORDER BY starttime DESC";
            $modelRestricted1hour = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS LAST 1 HOUR ", 15);

            if ($this->tariffObj[0]['1h'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestricted1hour->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS LAST 1 HOUR", 1);
                    $agi->execute((congestion), Congestion);
                    $agi->congestion($agi);
                }
            }
        }

        // #####################################
        // 4 HOUR
        // #####################################
        
        if ($this->tariffObj[0]['4h'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT * FROM ( SELECT pkg_cdr.id, pkg_cdr.calledstation, pkg_cdr.starttime FROM pkg_cdr WHERE pkg_cdr.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr.calledstation = '{$destination}' UNION ALL SELECT pkg_cdr_failed.id, pkg_cdr_failed.calledstation, pkg_cdr_failed.starttime FROM pkg_cdr_failed WHERE pkg_cdr_failed.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr_failed.calledstation = '{$destination}' AND pkg_cdr_failed.terminatecauseid != '5' AND pkg_cdr_failed.terminatecauseid != '6' ORDER BY starttime DESC ) AS TT GROUP BY calledstation ORDER BY starttime DESC";
            $modelRestricted4hour = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS LAST 4 HOUR ", 15);

            if ($this->tariffObj[0]['4h'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestricted4hour->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS LAST 4 HOUR", 1);
                    $agi->execute((congestion), Congestion);
                    $agi->congestion($agi);
                }
            }
        }


        // #####################################
        // 1 DAY
        // #####################################
        
        if ($this->tariffObj[0]['1d'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT * FROM ( SELECT pkg_cdr.id, pkg_cdr.calledstation, pkg_cdr.starttime FROM pkg_cdr WHERE pkg_cdr.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr.calledstation = '{$destination}' UNION ALL SELECT pkg_cdr_failed.id, pkg_cdr_failed.calledstation, pkg_cdr_failed.starttime FROM pkg_cdr_failed WHERE pkg_cdr_failed.starttime BETWEEN DATE_ADD(NOW(), INTERVAL -200 MINUTE) AND NOW() AND pkg_cdr_failed.calledstation = '{$destination}' AND pkg_cdr_failed.terminatecauseid != '5' AND pkg_cdr_failed.terminatecauseid != '6' ORDER BY starttime DESC ) AS TT GROUP BY calledstation ORDER BY starttime DESC";
            $modelRestricted1day = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS LAST 1 DAY ", 15);

            if ($this->tariffObj[0]['1d'] == 1) {
                /* NOT ALLOW TO CALL RESTRICTED NUMBERS*/
                if (isset($modelRestricted1day->id)) {
                    /* NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT AUHTORIZED - NOT ALLOW TO CALL RESTRICTED NUMBERS LAST 1 DAY", 1);
                    $agi->execute((congestion), Congestion);
                    $agi->congestion($agi);
                }
            }
        }

        // #####################################
        // HISTORY
        // #####################################

        if ($this->tariffObj[0]['id_trunk_history'] == 1) {
            $sql = "SELECT * FROM (SELECT pkg_cdr.id_trunk, pkg_cdr.iccid, pkg_trunk_group_trunk.id_trunk_group, pkg_cdr.starttime FROM pkg_cdr INNER JOIN pkg_trunk_group_trunk ON pkg_cdr.id_trunk = pkg_trunk_group_trunk.id_trunk WHERE pkg_cdr.starttime BETWEEN date_add(NOW(), INTERVAL -12 HOUR) AND NOW() AND pkg_cdr.calledstation = '{$destination}' AND pkg_trunk_group_trunk.id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND pkg_trunk_group_trunk.OK < 6 AND pkg_trunk_group_trunk.NOK < 18 AND pkg_trunk_group_trunk.status = 'up' UNION ALL SELECT pkg_cdr_failed.id_trunk, pkg_cdr_failed.iccid, pkg_trunk_group_trunk.id_trunk_group, pkg_cdr_failed.starttime FROM pkg_cdr_failed INNER JOIN pkg_trunk_group_trunk ON pkg_cdr_failed.id_trunk = pkg_trunk_group_trunk.id_trunk WHERE pkg_cdr_failed.starttime BETWEEN date_add(NOW(), INTERVAL -12 HOUR) AND NOW() AND pkg_cdr_failed.calledstation = '{$destination}' AND pkg_trunk_group_trunk.id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND pkg_cdr_failed.terminatecauseid != '5' AND pkg_cdr_failed.terminatecauseid != '6' AND pkg_trunk_group_trunk.OK < 6 AND pkg_trunk_group_trunk.NOK < 18 AND pkg_trunk_group_trunk.status = 'up' ORDER BY starttime DESC) AS TT Group by id_trunk ORDER BY starttime DESC LIMIT " . $this->tariffObj[0]['History_Number'] . " ";
        } else if ($this->tariffObj[0]['id_trunk_history'] == 0) {
            $sql = "SELECT * FROM (SELECT pkg_cdr.id_trunk, pkg_cdr.iccid, pkg_trunk_group_trunk.id_trunk_group, pkg_cdr.starttime FROM pkg_cdr INNER JOIN pkg_trunk_group_trunk ON pkg_cdr.id_trunk = pkg_trunk_group_trunk.id_trunk WHERE pkg_cdr.starttime BETWEEN date_add(NOW(), INTERVAL -1 SECOND) AND NOW() AND pkg_cdr.calledstation = '{$destination}' AND pkg_trunk_group_trunk.id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND pkg_trunk_group_trunk.OK < 6 AND pkg_trunk_group_trunk.NOK < 18 AND pkg_trunk_group_trunk.status = 'up' UNION ALL SELECT pkg_cdr_failed.id_trunk, pkg_cdr_failed.iccid, pkg_trunk_group_trunk.id_trunk_group, pkg_cdr_failed.starttime FROM pkg_cdr_failed INNER JOIN pkg_trunk_group_trunk ON pkg_cdr_failed.id_trunk = pkg_trunk_group_trunk.id_trunk WHERE pkg_cdr_failed.starttime BETWEEN date_add(NOW(), INTERVAL -1 SECOND) AND NOW() AND pkg_cdr_failed.calledstation = '{$destination}' AND pkg_trunk_group_trunk.id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND pkg_cdr_failed.terminatecauseid != '5' AND pkg_cdr_failed.terminatecauseid != '6' AND pkg_trunk_group_trunk.OK < 6 AND pkg_trunk_group_trunk.NOK < 18 AND pkg_trunk_group_trunk.status = 'up' ORDER BY starttime DESC) AS TT Group by id_trunk ORDER BY starttime DESC LIMIT " . $this->tariffObj[0]['History_Number'] . " ";
        }
        
        $modelTrunks = $agi->query($sql)->fetchAll(PDO::FETCH_OBJ);
        foreach ($modelTrunks as $key => $trunk) {

            $sql        = "SELECT *, pkg_trunk.id id  FROM pkg_trunk JOIN pkg_provider ON id_provider = pkg_provider.id WHERE pkg_trunk.id = " . $trunk->id_trunk . " LIMIT 1";
            $modelTrunk = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $this->usedtrunk   = $modelTrunk->id;
            $prefix            = $modelTrunk->trunkprefix;
            $tech              = $modelTrunk->providertech;
            $trunkcode         = $modelTrunk->trunkcode;
            $removeprefix      = $modelTrunk->removeprefix;
            $timeout           = $this->tariffObj[0]['timeout'];
            $addparameter      = $modelTrunk->addparameter;
            $inuse             = $modelTrunk->inuse;
            $maxuse            = $modelTrunk->maxuse;
            $allow_error       = $modelTrunk->allow_error;
            $status            = $modelTrunk->status;
            $this->id_provider = $modelTrunk->id_provider;
            $provider_credit   = $modelTrunk->credit;
            $this->iccid       = $modelTrunk->iccid;

            if ($typecall == 1) {
                $timeout = 3600;
            }

            if ($modelTrunk->credit_control == 1 && $provider_credit <= 0) {
                $agi->verbose("Provider not have credit", 3);
                continue;
            }

            if ($status == 0) {
                $agi->verbose("Trunk is inactive", 3);
                continue;
            }

            $this->sendCalltoTrunk($MAGNUS, $agi, $destination, $prefix, $tech, $trunkcode, $removeprefix, $timeout
                , $addparameter, $inuse, $maxuse, $allow_error);

            if ($this->dialstatus == "CANCEL" || $this->dialstatus == "NOANSWER" || $this->dialstatus == "BUSY") {
                $this->real_answeredtime = $this->answeredtime = 0;
                break;
            } else if ($this->dialstatus == "CHANUNAVAIL" || $this->dialstatus == "CONGESTION") {
                $this->real_answeredtime = $this->answeredtime = 0;
            } else {
                break;
            }

        } 

        //# Ooh, something actually happened!
        if ($this->dialstatus == "BUSY") {
            $this->real_answeredtime = $this->answeredtime = 0;
            $agi->execute((busy), busy);
            return true;

        } elseif ($this->dialstatus == "NOANSWER") {
            $this->real_answeredtime = $this->answeredtime = 0;
            $agi->execute((congestion), Congestion);
            return true;

        } elseif ($this->dialstatus == "CANCEL") {
            $this->real_answeredtime = $this->answeredtime = 0;
            return true;

        } elseif (($this->dialstatus == "CHANUNAVAIL") || ($this->dialstatus == "CONGESTION")) {
            $this->real_answeredtime = $this->answeredtime = 0;
        }
        // #####################################
        // TRUNK GROUP
        // #####################################


        if ($this->tariffObj[0]['WL1'] == 0 || $this->tariffObj[0]['WL1'] == 1) {
            /*Check if Account have restriction*/

            $sql = "SELECT id FROM pkg_restrict_phone WHERE id_user = $MAGNUS->id_user AND number = SUBSTRING('" . $destination . "',1,length(number)) ORDER BY LENGTH(number) DESC";

            $modelRestrictedPhonenumber = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $agi->verbose("RESTRICTED NUMBERS WL1", 15);

            if ($this->tariffObj[0]['WL1'] == 0) {

                $agi->verbose("NUMBER AUHTORIZED SIN WL1", 1);

                if ($this->tariffObj[0]['trunk_group_type'] == 1) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 10 SECOND AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 2) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 30 SECOND AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 3) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 1 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 4) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 2 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 5) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 3 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 6) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 5 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 7) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 10 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 8) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 15 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 9) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 30 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 10) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 1 HOUR AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 11) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 2 HOUR AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 12) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 4 HOUR AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 13) {
                    $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 10 SECOND AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
        
                } else if ($this->tariffObj[0]['trunk_group_type'] == 14) {
                    $sql = "SELECT *, (SELECT buyrate FROM pkg_rate_provider WHERE id_provider = tr.id_provider AND id_prefix = " . $this->tariffObj[0]['id_prefix'] . " LIMIT 1) AS buyrate  FROM pkg_trunk_group_trunk t  JOIN pkg_trunk tr ON t.id_trunk = tr.id WHERE id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " ORDER BY buyrate IS NULL , buyrate ";
                }
                $modelTrunks = $agi->query($sql)->fetchAll(PDO::FETCH_OBJ);
                   
                foreach ($modelTrunks as $key => $trunk) {
        
                    $sql        = "SELECT *, pkg_trunk.id id  FROM pkg_trunk JOIN pkg_provider ON id_provider = pkg_provider.id WHERE pkg_trunk.id = " . $trunk->id_trunk . " LIMIT 1";
                    $modelTrunk = $agi->query($sql)->fetch(PDO::FETCH_OBJ);
        
                    $this->usedtrunk   = $modelTrunk->id;
                    $prefix            = $modelTrunk->trunkprefix;
                    $tech              = $modelTrunk->providertech;
                    $trunkcode         = $modelTrunk->trunkcode;
                    $removeprefix      = $modelTrunk->removeprefix;
                    $timeout           = $this->tariffObj[0]['timeout'];
                    $addparameter      = $modelTrunk->addparameter;
                    $inuse             = $modelTrunk->inuse;
                    $maxuse            = $modelTrunk->maxuse;
                    $allow_error       = $modelTrunk->allow_error;
                    $status            = $modelTrunk->status;
                    $this->id_provider = $modelTrunk->id_provider;
                    $provider_credit   = $modelTrunk->credit;
                    $this->iccid       = $modelTrunk->iccid;
        
                    if ($typecall == 1) {
                        $timeout = 3600;
                    }
        
                    if ($modelTrunk->credit_control == 1 && $provider_credit <= 0) {
                        $agi->verbose("Provider not have credit", 3);
                        continue;
                    }
        
                    if ($status == 0) {
                        $agi->verbose("Trunk is inactive", 3);
                        continue;
                    }
        
                    $this->sendCalltoTrunk($MAGNUS, $agi, $destination, $prefix, $tech, $trunkcode, $removeprefix, $timeout
                        , $addparameter, $inuse, $maxuse, $allow_error);
        
                    if ($this->dialstatus == "CANCEL" || $this->dialstatus == "NOANSWER" || $this->dialstatus == "BUSY") {
                        $this->real_answeredtime = $this->answeredtime = 0;
                        break;
                    } else if ($this->dialstatus == "CHANUNAVAIL" || $this->dialstatus == "CONGESTION") {
                        $this->real_answeredtime = $this->answeredtime = 0;
                    } else {
                        break;
                    }
        
                } 
            
            } else if ($this->tariffObj[0]['WL1'] == 1) {
                /* ALLOW TO CALL ONLY RESTRICTED NUMBERS */
                if (isset($modelRestrictedPhonenumber->id)) {
                    /* NUMBER AUHTORIZED*/
                    $agi->verbose("NUMBER AUHTORIZED", 1);

                    if ($this->tariffObj[0]['trunk_group_type'] == 1) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 10 SECOND AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 2) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 30 SECOND AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 3) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 1 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 4) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 2 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 5) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 3 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 6) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 5 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 7) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 10 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 8) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 15 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 9) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 30 MINUTE AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 10) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 1 HOUR AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 11) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 2 HOUR AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 12) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 4 HOUR AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 12) {
                        $sql = "SELECT * FROM ( SELECT C.id_trunk, O.trunkcode, id_trunk_group, starttime FROM pkg_trunk_group_trunk C INNER JOIN pkg_trunk O ON C.id_trunk = O.id WHERE starttime <= NOW() - INTERVAL 10 SECOND AND id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " AND C.OK < 6 AND C.NOK < 18 AND C.status = 'up') AS CO LEFT OUTER JOIN pkg_call_online D ON CO.trunkcode = D.tronco WHERE D.tronco IS NULL ORDER BY starttime ASC";
            
                    } else if ($this->tariffObj[0]['trunk_group_type'] == 14) {
                        $sql = "SELECT *, (SELECT buyrate FROM pkg_rate_provider WHERE id_provider = tr.id_provider AND id_prefix = " . $this->tariffObj[0]['id_prefix'] . " LIMIT 1) AS buyrate  FROM pkg_trunk_group_trunk t  JOIN pkg_trunk tr ON t.id_trunk = tr.id WHERE id_trunk_group = " . $this->tariffObj[0]['id_trunk_group'] . " ORDER BY buyrate IS NULL , buyrate ";
                    }
                    $modelTrunks = $agi->query($sql)->fetchAll(PDO::FETCH_OBJ);
            
                    foreach ($modelTrunks as $key => $trunk) {
            
                        $sql        = "SELECT *, pkg_trunk.id id  FROM pkg_trunk JOIN pkg_provider ON id_provider = pkg_provider.id WHERE pkg_trunk.id = " . $trunk->id_trunk . " LIMIT 1";
                        $modelTrunk = $agi->query($sql)->fetch(PDO::FETCH_OBJ);
            
                        $this->usedtrunk   = $modelTrunk->id;
                        $prefix            = $modelTrunk->trunkprefix;
                        $tech              = $modelTrunk->providertech;
                        $trunkcode         = $modelTrunk->trunkcode;
                        $removeprefix      = $modelTrunk->removeprefix;
                        $timeout           = $this->tariffObj[0]['timeout'];
                        $addparameter      = $modelTrunk->addparameter;
                        $inuse             = $modelTrunk->inuse;
                        $maxuse            = $modelTrunk->maxuse;
                        $allow_error       = $modelTrunk->allow_error;
                        $status            = $modelTrunk->status;
                        $this->id_provider = $modelTrunk->id_provider;
                        $provider_credit   = $modelTrunk->credit;
                        $this->iccid       = $modelTrunk->iccid;
            
                        if ($typecall == 1) {
                            $timeout = 3600;
                        }
            
                        if ($modelTrunk->credit_control == 1 && $provider_credit <= 0) {
                            $agi->verbose("Provider not have credit", 3);
                            continue;
                        }
            
                        if ($status == 0) {
                            $agi->verbose("Trunk is inactive", 3);
                            continue;
                        }
            
                        $this->sendCalltoTrunk($MAGNUS, $agi, $destination, $prefix, $tech, $trunkcode, $removeprefix, $timeout
                            , $addparameter, $inuse, $maxuse, $allow_error);
            
                        if ($this->dialstatus == "CANCEL" || $this->dialstatus == "NOANSWER" || $this->dialstatus == "BUSY") {
                            $this->real_answeredtime = $this->answeredtime = 0;
                            break;
                        } else if ($this->dialstatus == "CHANUNAVAIL" || $this->dialstatus == "CONGESTION") {
                            $this->real_answeredtime = $this->answeredtime = 0;
                        } else {
                            break;
                        }
            
                    } 
                    
                }

            } else if ($this->tariffObj[0]['WL1'] == 1) {
                /* ALLOW TO CALL ONLY RESTRICTED NUMBERS */
                if (!isset($modelRestrictedPhonenumber->id)) {
                    /*NUMBER NOT AUHTORIZED*/
                    $agi->verbose("NUMBER NOT IN WL1", 1);

                    // $agi->execute((congestion), Congestion);
                    // $this->hangup($agi);            
                }
            }
            //# Ooh, something actually happened!
            if ($this->dialstatus == "BUSY") {
                $this->real_answeredtime = $this->answeredtime = 0;
                $agi->execute((busy), busy);
                return true;

            } elseif ($this->dialstatus == "NOANSWER") {
                $this->real_answeredtime = $this->answeredtime = 0;
                $agi->execute((congestion), Congestion);
                return true;

            } elseif ($this->dialstatus == "CANCEL") {
                $this->real_answeredtime = $this->answeredtime = 0;
                return true;
    
            } elseif (($this->dialstatus == "CHANUNAVAIL") || ($this->dialstatus == "CONGESTION")) {
                $this->real_answeredtime = $this->answeredtime = 0;
            } else {
                $agi->execute((congestion), Congestion);
                return true;

            }

        }
    }

    public function sendCalltoTrunk($MAGNUS, $agi, $destination, $prefix, $tech, $ipaddress, $removeprefix, $timeout
        , $addparameter, $inuse, $maxuse, $allow_error) {

        if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0 || substr(strtoupper($removeprefix), 0, 1) == "X") {
            $destination = substr($destination, strlen($removeprefix));
        }

        if ($MAGNUS->agiconfig['switchdialcommand'] == 1 || $tech == 'Local') {
            $dialstr = "$tech/$prefix$destination@$ipaddress";
        } else {
            $dialstr = "$tech/$ipaddress/$prefix$destination";
        }

        $dialedpeername       = $agi->get_variable("SIPTRANSFER");
        $this->dialedpeername = $dialedpeername['data'];

        if ($this->dialedpeername == 'yes') {
            $agi->execute("hangup request $this->channel");
            $MAGNUS->hangup($agi);
        }

        $MAGNUS->startRecordCall($agi);
        try {
            $MAGNUS->run_dial($agi, $dialstr, $MAGNUS->agiconfig['dialcommand_param'] . $addparameter
                , $this->tariffObj[0]['rc_directmedia'], $timeout);
        } catch (Exception $e) {
            //
        }

        if ($MAGNUS->is_callingcard == true) {
            $answeredtime = $agi->get_variable("TRUNKANSWERTIME");

            $this->real_answeredtime = $this->answeredtime = time() - $answeredtime['data'];
        } else {
            $answeredtime            = $agi->get_variable("ANSWEREDTIME");
            $this->real_answeredtime = $this->answeredtime = $answeredtime['data'];
        }

        $dialstatus       = $agi->get_variable("DIALSTATUS");
        $this->dialstatus = $dialstatus['data'];

        $MAGNUS->stopRecordCall($agi);
    }

    public function callShop($agi, $MAGNUS, $sessiontime, $id_prefix, $cost)
    {

        if ($MAGNUS->callshop == 1) {
            if ($sessiontime > 0) {
                $sql = "SELECT * FROM pkg_rate_callshop WHERE dialprefix = SUBSTRING($MAGNUS->destination,1,length(dialprefix))
                                AND id_user= $MAGNUS->id_user   ORDER BY LENGTH(dialprefix) DESC LIMIT 1";
                $modelReteCallshop = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

                if (!isset($modelReteCallshop->id)) {
                    $agi->verbose('Not found CallShop rate => ' . $MAGNUS->destination . ' ' . $MAGNUS->id_user);
                    return;
                }
                $buyrate   = $modelReteCallshop->buyrate > 0 ? $modelReteCallshop->buyrate : $cost;
                $initblock = $modelReteCallshop->minimo;
                $increment = $modelReteCallshop->block;

                $sellratecost_callshop = $MAGNUS->calculation_price($buyrate, $sessiontime, $initblock, $increment);

                if ($sessiontime < $modelReteCallshop->minimal_time_charge) {
                    $agi->verbose("Minimal time to charge. Cost 0.0000", 15);
                    $sellratecost_callshop = 0;
                }

                //save in CDRCALLSHOP

                $fields = "sessionid, id_user, status, price, buycost, calledstation, destination,price_min, cabina, sessiontime";
                $values = "'$MAGNUS->channel', $MAGNUS->id_user, 0, $sellratecost_callshop, '$cost', '$MAGNUS->destination',
                                '" . $modelReteCallshop->destination . "', '$buyrate', '$MAGNUS->sip_account', $sessiontime";
                $sql = "INSERT INTO pkg_callshop ($fields) VALUES ($values)";
                $agi->exec($sql);
            }
        }
        return;
    }

    public function saveCDR($agi, $MAGNUS, $returnID = false)
    {

        /*
        $MAGNUS->uniqueid = ;
        $MAGNUS->id_user = ;
        $MAGNUS->destination = ;
        $MAGNUS->id_plan = ;
        $MAGNUS->id_trunk = ;
        $MAGNUS->sip_account = ;
        $CalcAgi->starttime        = date("Y-m-d H:i:s", $startCall);
        $CalcAgi->sessiontime      = $answeredtime;
        $CalcAgi->real_sessiontime = $answeredtime;
        $CalcAgi->terminatecauseid = $terminatecauseid;
        $CalcAgi->sessionbill      = $cost;
        $CalcAgi->sipiax           = $sipiax;
        $CalcAgi->buycost          = 0;
        $CalcAgi->id_prefix        = null;
        $CalcAgi->saveCDR($agi, $MAGNUS);
         */

        if ($this->sipiax == 3 && !preg_match('/\_WT/', $MAGNUS->sip_account)) {
            //if call is a DID, check is sipaccount is valid, else, set the callerid
            $sql             = "SELECT name FROM pkg_sip WHERE name  = '" . $MAGNUS->sip_account . "' LIMIT 1";
            $modelSipaccount = $agi->query($sql)->fetch(PDO::FETCH_OBJ);
            if (!isset($modelSipaccount->name)) {
                $MAGNUS->sip_account = $MAGNUS->CallerID;
            }
        }



        if ($this->terminatecauseid == 1) {

            $fields = "uniqueid,id_user,calledstation,id_plan,callerid,src,
                        starttime,sessiontime,real_sessiontime, terminatecauseid,sessionbill,
                        sipiax,buycost,iccid";

            $values = "'$MAGNUS->uniqueid', $MAGNUS->id_user, '$MAGNUS->destination', $MAGNUS->id_plan, '$MAGNUS->CallerID',
                        '$MAGNUS->sip_account',
                        '$this->starttime', '$this->sessiontime',
                        '$this->real_sessiontime', '$this->terminatecauseid', '$this->sessionbill',
                        '$this->sipiax','$this->buycost','$this->iccid'";

            if (is_numeric($MAGNUS->id_trunk)) {
                $fields .= ', id_trunk';
                $values .= ", $MAGNUS->id_trunk";
            }
            if (is_numeric($this->id_prefix)) {
                $fields .= ', id_prefix';
                $values .= ", $this->id_prefix";
            }
            if ($this->id_campaign > 0) {
                $fields .= ', id_campaign';
                $values .= ", $this->id_campaign";
            }
            if ($this->agent_bill > 0) {
                $fields .= ', agent_bill';
                $values .= ", $this->agent_bill";
            }
            $sql = "INSERT INTO pkg_cdr ($fields) VALUES ($values) ";
            $agi->exec($sql);

            $sql = "UPDATE pkg_provider SET credit = credit - $this->buycost WHERE id=" . $this->id_provider . " LIMIT 1;";
            $agi->exec($sql);


            $sql = "UPDATE pkg_trunk_group_trunk SET starttime = '" . date('Y-m-d H:i:s') . "' , OK = OK + 1 WHERE iccid = '$this->iccid' ";
            $agi->exec($sql);

            if ($returnID == true) {
                return $agi->lastInsertId();
            }
        } else if ($this->terminatecauseid == 2) {
            $keys        = $agi->get_variable("HANGUPCAUSE_KEYS()", true);
            $tech_string = explode(",", $keys);
            foreach ($tech_string as $key => $value) {
                if (preg_match('/' . $this->trunkcode . '/', $value)) {
                    $TECHSTRING = $value;
                    break;
                }
            }
            $code   = substr($agi->get_variable('HANGUPCAUSE(' . $TECHSTRING . ',tech)', true), 4, 3);
            $fields = "uniqueid,id_user,calledstation,id_plan,id_trunk,callerid,src,
                    starttime, terminatecauseid,sipiax,id_prefix,hangupcause,iccid";
            $values = "'$MAGNUS->uniqueid', '$MAGNUS->id_user','$MAGNUS->destination','$MAGNUS->id_plan',
                    '$MAGNUS->id_trunk','$MAGNUS->CallerID', '$MAGNUS->sip_account',
                    '$this->starttime', '$this->terminatecauseid','$this->sipiax','$this->id_prefix','$code','$this->iccid'";
            $sql = "INSERT INTO pkg_cdr_failed ($fields) VALUES ($values) ";
            $agi->exec($sql);

            $sql = "UPDATE pkg_trunk_group_trunk SET starttime = '" . date('Y-m-d H:i:s') . "' , NOK = NOK + 1 WHERE iccid = '$this->iccid' ";
            $agi->exec($sql);


        } else if ($this->terminatecauseid == 3) {
            $keys        = $agi->get_variable("HANGUPCAUSE_KEYS()", true);
            $tech_string = explode(",", $keys);
            foreach ($tech_string as $key => $value) {
                if (preg_match('/' . $this->trunkcode . '/', $value)) {
                    $TECHSTRING = $value;
                    break;
                }
            }
            $code   = substr($agi->get_variable('HANGUPCAUSE(' . $TECHSTRING . ',tech)', true), 4, 3);
            $fields = "uniqueid,id_user,calledstation,id_plan,id_trunk,callerid,src,
                    starttime, terminatecauseid,sipiax,id_prefix,hangupcause,iccid";
            $values = "'$MAGNUS->uniqueid', '$MAGNUS->id_user','$MAGNUS->destination','$MAGNUS->id_plan',
                    '$MAGNUS->id_trunk','$MAGNUS->CallerID', '$MAGNUS->sip_account',
                    '$this->starttime', '$this->terminatecauseid','$this->sipiax','$this->id_prefix','$code','$this->iccid'";
            $sql = "INSERT INTO pkg_cdr_failed ($fields) VALUES ($values) ";
            $agi->exec($sql);

            $sql = "UPDATE pkg_trunk_group_trunk SET starttime = '" . date('Y-m-d H:i:s') . "' , NOK = NOK + 1 WHERE iccid = '$this->iccid' ";
            $agi->exec($sql);


        } else if ($this->terminatecauseid == 4) {
            $keys        = $agi->get_variable("HANGUPCAUSE_KEYS()", true);
            $tech_string = explode(",", $keys);
            foreach ($tech_string as $key => $value) {
                if (preg_match('/' . $this->trunkcode . '/', $value)) {
                    $TECHSTRING = $value;
                    break;
                }
            }
            $code   = substr($agi->get_variable('HANGUPCAUSE(' . $TECHSTRING . ',tech)', true), 4, 3);
            $fields = "uniqueid,id_user,calledstation,id_plan,id_trunk,callerid,src,
                    starttime, terminatecauseid,sipiax,id_prefix,hangupcause,iccid";
            $values = "'$MAGNUS->uniqueid', '$MAGNUS->id_user','$MAGNUS->destination','$MAGNUS->id_plan',
                    '$MAGNUS->id_trunk','$MAGNUS->CallerID', '$MAGNUS->sip_account',
                    '$this->starttime', '$this->terminatecauseid','$this->sipiax','$this->id_prefix','$code','$this->iccid'";
            $sql = "INSERT INTO pkg_cdr_failed ($fields) VALUES ($values) ";
            $agi->exec($sql);

            $sql = "UPDATE pkg_trunk_group_trunk SET starttime = '" . date('Y-m-d H:i:s') . "' , NOK = NOK + 1 WHERE iccid = '$this->iccid' ";
            $agi->exec($sql);


        } else if ($this->terminatecauseid == 5) {
            $keys        = $agi->get_variable("HANGUPCAUSE_KEYS()", true);
            $tech_string = explode(",", $keys);
            foreach ($tech_string as $key => $value) {
                if (preg_match('/' . $this->trunkcode . '/', $value)) {
                    $TECHSTRING = $value;
                    break;
                }
            }
            $code   = substr($agi->get_variable('HANGUPCAUSE(' . $TECHSTRING . ',tech)', true), 4, 3);
            $fields = "uniqueid,id_user,calledstation,id_plan,id_trunk,callerid,src,
                    starttime, terminatecauseid,sipiax,id_prefix,hangupcause,iccid";
            $values = "'$MAGNUS->uniqueid', '$MAGNUS->id_user','$MAGNUS->destination','$MAGNUS->id_plan',
                    '$MAGNUS->id_trunk','$MAGNUS->CallerID', '$MAGNUS->sip_account',
                    '$this->starttime', '$this->terminatecauseid','$this->sipiax','$this->id_prefix','$code','$this->iccid'";
            $sql = "INSERT INTO pkg_cdr_failed ($fields) VALUES ($values) ";
            $agi->exec($sql);

        } else if ($this->terminatecauseid == 6) {
            $keys        = $agi->get_variable("HANGUPCAUSE_KEYS()", true);
            $tech_string = explode(",", $keys);
            foreach ($tech_string as $key => $value) {
                if (preg_match('/' . $this->trunkcode . '/', $value)) {
                    $TECHSTRING = $value;
                    break;
                }
            }
            $code   = substr($agi->get_variable('HANGUPCAUSE(' . $TECHSTRING . ',tech)', true), 4, 3);
            $fields = "uniqueid,id_user,calledstation,id_plan,id_trunk,callerid,src,
                    starttime, terminatecauseid,sipiax,id_prefix,hangupcause";
            $values = "'$MAGNUS->uniqueid', '$MAGNUS->id_user','$MAGNUS->destination','$MAGNUS->id_plan',
                    '$MAGNUS->id_trunk','$MAGNUS->CallerID', '$MAGNUS->sip_account',
                    '$this->starttime', '$this->terminatecauseid','$this->sipiax','$this->id_prefix','$code'";

            $sql = "INSERT INTO pkg_cdr_failed ($fields) VALUES ($values) ";
            $agi->exec($sql);

        } else {

            if (file_exists(dirname(__FILE__) . '/CallCache.php')) {
                include 'CallCache.php';
            } else {
                $keys        = $agi->get_variable("HANGUPCAUSE_KEYS()", true);
                $tech_string = explode(",", $keys);
                foreach ($tech_string as $key => $value) {
                    if (preg_match('/' . $this->trunkcode . '/', $value)) {
                        $TECHSTRING = $value;
                        break;
                    }
                }
                $code   = substr($agi->get_variable('HANGUPCAUSE(' . $TECHSTRING . ',tech)', true), 4, 3);
                $fields = "uniqueid,id_user,calledstation,id_plan,id_trunk,callerid,src,
                        starttime, terminatecauseid,sipiax,id_prefix,hangupcause";

                $values = "'$MAGNUS->uniqueid', '$MAGNUS->id_user','$MAGNUS->destination','$MAGNUS->id_plan',
                        '$MAGNUS->id_trunk','$MAGNUS->CallerID', '$MAGNUS->sip_account',
                        '$this->starttime', '$this->terminatecauseid','$this->sipiax','$this->id_prefix','$code'";

                $sql = "INSERT INTO pkg_cdr_failed ($fields) VALUES ($values) ";
                $agi->exec($sql);
            }
        }
    }
}