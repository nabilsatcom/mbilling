<?php
/**
 * =======================================
 * ###################################
 *
 *
 *
 */
class SearchTariff
{

    public function find(&$MAGNUS, &$agi)
    {

        if (file_exists(dirname(__FILE__) . '/beforeSearchTariff.php')) {
            include dirname(__FILE__) . '/beforeSearchTariff.php';
        }

        $max_len_prefix       = strlen($MAGNUS->destination);
        $MAGNUS->prefixclause = '(';
        while ($max_len_prefix >= 1) {
            $MAGNUS->prefixclause .= "prefix='" . substr($MAGNUS->destination, 0, $max_len_prefix) . "' OR ";
            $max_len_prefix--;
        }

        $MAGNUS->prefixclause = substr($MAGNUS->prefixclause, 0, -3) . ")";

        $sql = "SELECT  pkg_plan.id AS id_plan, pkg_prefix.prefix AS dialprefix, " .
        "pkg_plan.name, pkg_rate.id_prefix, pkg_rate.id AS id_rate, minimal_time_charge, " .
        "rateinitial, initblock, billingblock, connectcharge, disconnectcharge disconnectcharge, " .
        "pkg_rate.additional_grace AS additional_grace, package_offer, FRCalled, FRCaller, FNCalled, FNCaller, id_trunk_history, History_Number, WL1, 2min, att2min, 5min, att5min, 15min, att15min, 1h, att1h, 4h, att4h, 1d, att1d ,id_trunk_group, pkg_trunk_group.type AS trunk_group_type " .
        "FROM pkg_plan " .
        "LEFT JOIN pkg_rate ON pkg_plan.id = pkg_rate.id_plan " .
        "LEFT JOIN pkg_prefix ON pkg_rate.id_prefix = pkg_prefix.id " .
        "LEFT JOIN pkg_trunk_group ON pkg_trunk_group.id = pkg_rate.id_trunk_group " .
        "WHERE pkg_plan.id=$MAGNUS->id_plan AND pkg_rate.status = 1 AND " . $MAGNUS->prefixclause .
            "ORDER BY LENGTH( prefix ) DESC LIMIT 1";
        $result = $agi->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        if (!is_array($result) || count($result) == 0) {
            return 0;
        }

        //Select custom rate to user
        $sql           = "SELECT * FROM pkg_user_rate WHERE id_user = $MAGNUS->id_user AND id_prefix = '" . $result[0]['id_prefix'] . "' LIMIT 1";
        $modelUserRate = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

        //change custom rate to user
        if (isset($modelUserRate->id)) {
            $result[0]['rateinitial']  = $modelUserRate->rateinitial;
            $result[0]['initblock']    = $modelUserRate->initblock;
            $result[0]['billingblock'] = $modelUserRate->billingblock;
        }

        if ($MAGNUS->sip_id_trunk_group > 0) {
            $agi->verbose('SIP USER have ' . $MAGNUS->sip_account . ' trunk group ' . $MAGNUS->sip_id_trunk_group, 5);
            $result[0]['id_trunk_group'] = $MAGNUS->sip_id_trunk_group;
        }

        if (file_exists(dirname(__FILE__) . '/AfterSearchTariff.php')) {
            include dirname(__FILE__) . '/AfterSearchTariff.php';
        }

        return $result;

    }
}
