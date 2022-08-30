<?php
$dbname = 'mbilling';
$dbuser = 'root';
$dbpass = 'gGeJDOE_qMiCqEEH';
$dbhost = 'localhost';
$connect = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Unable to connect to '$dbhost'");
mysqli_select_db($connect,$dbname) or die("Could not open the database '$dbname'");



$mapss = [
    "db00-0040-ad00-0056" => 1,
    "db00-0040-9800-0186" => 2,
    "db38-1022-9082-0088" => 3,
    "db00-0051-2100-0131" => 4,
    "db58-0330-3086-0008" => 11,
    "db58-0330-3086-0003" => 12,
        ];
$dinstar = file_get_contents('php://input');
        // # Para hacer test manual
        //  $content = ["sn"=>"db00-0051-2100-0131","register"=>[["port"=>0,"iccid"=>"8921202700238241467F","imsi"=>"604020175255440","number"=>"","status"=>"up","sequence"=>5]]];


$content = json_decode($dinstar,true);
$trunk= $mapss[
    $content[
        "sn"
        ]
    ];
$port= $content["register"][0]["port"] > 9 ? $content["register"][0]["port"]."0" : '0'.$content["register"][0]["port"]."0" ;

$trunkprefix = $trunk.$port;
$result = mysqli_query($connect,"SELECT id FROM pkg_trunk where trunkprefix = '$trunkprefix'");
$id = mysqli_fetch_array($result)['id'];
$iccid= $content["register"][0]["iccid"];
$status = $content["register"][0]["status"];

$update = mysqli_query($connect,"UPDATE pkg_trunk_group_trunk SET iccid = '$iccid', status = '$status' WHERE id_trunk = '$id' ");
var_dump($update);
$update1 = mysqli_query($connect,"UPDATE pkg_trunk SET iccid = '$iccid' WHERE id = '$id' ");
var_dump($update1);

?>
