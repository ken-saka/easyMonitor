<?php

$conf = parse_ini_file("/var/share/kanshi/lib/Conf.ini",true);
require("../lib/Db.php");
require("../lib/Alert.php");

$res = alert([
  'action'=>'select'
 ,'conf'=>$conf
 ,'con'=>$con
 ,'offset'=>0
 ,'count'=>10
]);

var_dump($res['list']);

/*
foreach($row = $res['list']){
  echo $row['occorDate'].","
      .$row['hostname'].","
      .$row['alert_id'].","
      .$row['alertLevel'].","
      .$row['alertContent'].","
      .$row['checked']."\n";
}
*/

?>
