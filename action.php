<?php

$conf = parse_ini_file("/var/share/html/easyMonitor/lib/Conf.ini",true);
require("./lib/Db.php");
require("./lib/Alert.php");
require("./lib/Host.php");
require("./lib/Monitor.php");

$act = htmlspecialchars($_GET["act"]);

switch ($act) {
  case "alertChecked":
    $alert_id = htmlspecialchars($_GET["alert_id"]);
    $checked = htmlspecialchars($_GET["checked"]);
    $res = _alert([
         'action'  =>'update'
        ,'conf'    =>$conf
        ,'con'     =>$con
        ,'alert_id'=>$alert_id
        ,'checked'=>$checked
     ]);
  break;

  case "alertListGetJson":
    $offset = htmlspecialchars($_GET["offset"]);
    $count = htmlspecialchars($_GET["count"]);
    $res = _alert([
      'action'   =>'select'
     ,'conf'     =>$conf
     ,'con'      =>$con
     ,'offset'   =>$offset
     ,'count'    =>$count
    ]);
    echo $res['list'];
  break;

  case "hostListGetJson":
    $offset = htmlspecialchars($_GET["offset"]);
    $count = htmlspecialchars($_GET["count"]);
    $res = _host([
      'action'=>'hostselect'
     ,'conf'  =>$conf
     ,'con'   =>$con
     ,'offset'=>$offset
     ,'count' =>$count
    ]);
    echo $res['list'];
  break;

  case "monitorListGetJson":
    $offset = htmlspecialchars($_GET["offset"]);
    $count = htmlspecialchars($_GET["count"]);
    $res = _monitor([
      'action'=>'monitorselect'
     ,'conf'=>$conf
     ,'con' =>$con
     ,'offset'=>$offset
     ,'count'=>$count
    ]);
    echo $res['list'];
  break;

  case "hostCreate":
    $hostname  = htmlspecialchars($_GET["hostname"]);
    $ipaddress = htmlspecialchars($_GET["ipaddress"]);
    $res = _host([
      'action'=>'hostinsert'
     ,'conf'=>$conf
     ,'con'=>$con
     ,'hostname'=>$hostname
     ,'ipaddress'=>$ipaddress
    ]);
    echo $res['list'];
  break;

  case "monitorCreate":
    $monitorname     = htmlspecialchars($_GET["monitorname"]);
    $monitortype     = htmlspecialchars($_GET["monitortype"]);
    $monitortimeout  = htmlspecialchars($_GET["monitortimeout"]);
    $monitorretry    = htmlspecialchars($_GET["monitorretry"]);
    $monitorargument = htmlspecialchars($_GET["monitorargument"]);
    $res = _monitor([
      'action'=>'monitorinsert'
     ,'conf'=>$conf
     ,'con'=>$con
     ,'monitorname'=>$monitorname
     ,'monitortype'=>$monitortype
     ,'timeout'    =>$monitortimeout
     ,'retry'      =>$monitorretry
     ,'argument'   =>$monitorargument
    ]);
    echo $res['list'];
  break;

  case "hostDelete":
    $host_id = htmlspecialchars($_GET["host_id"]);
    $res = _host([
      'action'=>'hostdelete'
     ,'conf'=>$conf
     ,'con'=>$con
     ,'host_id'=>$host_id
    ]);
    echo $res['list'];
  break;

  case "monitorDelete":
    $monitor_id = htmlspecialchars($_GET["monitor_id"]);
    $res = _monitor([
      'action'=>'monitordelete'
     ,'conf'=>$conf
     ,'con'=>$con
     ,'monitor_id'=>$monitor_id
    ]);
    echo $res['list'];
  break;
}

?>
