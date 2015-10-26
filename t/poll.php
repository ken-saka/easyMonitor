<?php

$conf = parse_ini_file("/var/share/html/easyMonitor/lib/Conf.ini",true);
require("../lib/Db.php");
require("../lib/Polling.php");
require("../lib/Alert.php");

$query = "GET / HTTP/1.0\r\nConnection: close\r\n\r\n";

$stmt = $con->query('SELECT * FROM tbl_host;');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

  print $row["hostname"]."-------------------------------\n";
  $get=[
     'case' => 'ping'
    ,'host' => $row["ipaddress"]
    ,'port' => 0
    ,'timeout' => 10 
    ,'arg' => ''
  ];
  $res = polling($get);
  print var_dump($res);
  if($res['code'] == 'NG'){
     alert([
         'conf'=>$conf
        ,'con'=>$con
        ,'action'=>'insert'
        ,'hostname'=>$row['hostname']
        ,'alertLevel_id'=>3
        ,'alertContent'=>'ping time out'
        ,'cheked'=>0
     ]);
  }
    

  $get=[
     'case' => 'http'
    ,'host' => $row["ipaddress"]
    ,'port' => 80
    ,'timeout' => 10 
    ,'arg' => $query
  ];
  $res = polling($get);
  print var_dump($res);
  if($res['code'] == 'NG'){
     alert([
         'conf'=>$conf
        ,'con'=>$con
        ,'action'=>'insert'
        ,'hostname'=>$row['hostname']
        ,'alertLevel_id'=>3
        ,'alertContent'=>'http time out'
        ,'cheked'=>0
     ]);
  }

  $get=[
     'case' => 'https'
    ,'host' => $row["ipaddress"]
    ,'port' => 443 
    ,'timeout' => 10 
    ,'arg' => $query
  ];
  $res = polling($get);
  print var_dump($res);
  if($res['code'] == 'NG'){
     alert([
         'conf'=>$conf
        ,'con'=>$con
        ,'action'=>'insert'
        ,'hostname'=>$row['hostname']
        ,'alertLevel_id'=>3
        ,'alertContent'=>'https time out'
        ,'cheked'=>0
     ]);
  }
}

?>
