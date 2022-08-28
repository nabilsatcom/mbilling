<?php
$dbname = 'one';
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

$update = mysqli_query($connect,"UPDATE pkg_trunk SET iccid = '$iccid', status_sn = '$status' WHERE id = '$id' ");

var_dump($update);




# $query = "INSERT INTO tbl_employee(name, gender, designation) VALUES ('".$row["name"]."', '".$row["gender"]."', '".$row["designation"]."'); ";  // Make Multiple Insert Query 


    // $result = mysqli_query($connect,"SELECT * FROM pkg_trunk");
    // while ($row = mysqli_fetch_array($result)) {
    //     printf("ID: %s  Name: %s <br>", $row[0], $row[1]);
    //     file_put_contents('/etc/asterisk/test.txt',$row[0]);
    // }



    // file_put_contents('/etc/asterisk/test.txt', file_get_contents('php://input'));
    // var_dump($_POST);
    // printf($_POST);
    // $json_string = json_encode($_POST);
?>
