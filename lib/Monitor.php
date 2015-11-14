<?php

/* ---------------------------------------------
input 
 $get
  conf
  con
  action
  monitorName
  monitorType
  argument
  timeout
  

create
  

output
  for DB
  $res
    result  : 0...ok, 1...ng
    list    : array

   ---------------------------------------------
*/

function _monitor($get){

  switch($get['action']){

    case 'monitorinsert':
      $stmt = $get['con']->prepare($get['conf']['Monitor']['INSERT']);
      $stmt->bindValue(':monitorname',$get['monitorname'], PDO::PARAM_STR);
      $stmt->bindValue(':monitortype',$get['monitortype'], PDO::PARAM_STR);
      $stmt->bindValue(':argument'   ,$get['argument'], PDO::PARAM_STR);
      $stmt->bindValue(':timeout'    ,$get['timeout'], PDO::PARAM_INT);
      $stmt->bindValue(':retry'      ,$get['retry'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result']=0;
      $monitor_id = $get['con']->lastInsertId();
      $res['list'] = json_encode("'monitor_id':$monitor_id");
    break;

    case 'monitorselect':
      $stmt = $get['con']->prepare($get['conf']['Monitor']['SELECT']);
      $stmt->bindValue(':offset' ,$get['offset'], PDO::PARAM_INT);
      $stmt->bindValue(':count'  ,$get['count'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result'] = !($stmt->rowCount());
      $rows = array();
      while($row = $stmt->fetch()){
        $rows[$row[0]] = array(
          'monitor_id'  => $row["monitor_id"]
         ,'monitorname' => $row["monitorName"]
         ,'monitortype' => $row["monitorType"]
         ,'timeout'     => $row["timeout"]
         ,'retry'       => $row["retry"]
         ,'argument'    => $row["argument"]
        );
      }
      $res['list'] = json_encode($rows);
    break;

    case 'monitorupdate':
      $stmt = $get['con']->prepare($get['conf']['Monitor']['UPDATE']);
      $stmt->bindValue(':checked',$get['checked'], PDO::PARAM_INT);
      $stmt->bindValue(':monitor_id',$get['monitor_id'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result']=0;
    break;

    case 'monitordelete':
      $stmt = $get['con']->prepare($get['conf']['Monitor']['DELETE']);
      $stmt->bindValue(':monitor_id',$get['monitor_id'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result']=0;
      $res['list']=$get['monitor_id'];
    break;
  }
  return $res;
}

?>
