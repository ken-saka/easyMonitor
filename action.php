<?php

$conf = parse_ini_file("/var/share/html/easyMonitor/lib/Conf.ini",true);
require("./lib/Db.php");
require("./lib/Alert.php");

$act = htmlspecialchars($_GET["act"]);

switch ($act) {
  case "alertChecked":
    $alert_id = htmlspecialchars($_GET["alert_id"]);
    $checked = htmlspecialchars($_GET["checked"]);
    alert([
         'action'=>'update'
        ,'conf'=>$conf
        ,'con'=>$con
        ,'alert_id'=>$alert_id
        ,'checked'=>$checked
     ]);
  break;
  case "alertListGetJson":
    $offset = htmlspecialchars($_GET["offset"]);
    $count = htmlspecialchars($_GET["count"]);
    $res = alert([
      'action'=>'select'
     ,'conf'=>$conf
     ,'con'=>$con
     ,'offset'=>$offset
     ,'count'=>$count
    ]);
    echo $res['list'];
  break;
}

?>
