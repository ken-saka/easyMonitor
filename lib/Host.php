<?php

/* ---------------------------------------------
input 

 $get
  conf
  con
  action
  hostname
  ipaddress

create
  

output
  for DB
  $res
    result  : 0...ok, 1...ng
    list    : array

   ---------------------------------------------
*/

function _host($get){

  switch($get['action']){

    case 'hostinsert':
      $stmt = $get['con']->prepare($get['conf']['Host']['HOSTINSERT']);
      $stmt->bindValue(':hostname'  ,$get['hostname'], PDO::PARAM_STR);
      $stmt->bindValue(':ipaddress' ,$get['ipaddress'], PDO::PARAM_STR);
      $stmt->execute();
      $res['result']=0;
    break;

    case 'moninsert':
      $stmt = $get['con']->prepare($get['conf']['Host']['MONINSERT']);
      $stmt->bindValue(':host_id'    ,$get['host_id'], PDO::PARAM_INT);
      $stmt->bindValue(':monitorName',$get['monitorName'], PDO::PARAM_STR);
      $stmt->bindValue(':monitorType',$get['monitorType'], PDO::PARAM_STR);
      $stmt->bindValue(':argument'   ,$get['argument'], PDO::PARAM_STR);
      $stmt->bindValue(':timeout'    ,$get['timeout'], PDO::PARAM_STR);
      $stmt->execute();
      $res['result']=0;
    break;

    case 'hostselect':
      $stmt = $get['con']->prepare($get['conf']['Host']['HOSTSELECT']);
      $stmt->bindValue(':offset'   ,$get['offset'], PDO::PARAM_INT);
      $stmt->bindValue(':count'    ,$get['count'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result'] = !($stmt->rowCount());
      $rows = array();
      while($row = $stmt->fetch()){
        $rows[] = $row;
      }
      $res['list'] = json_encode($rows);
    break;

    case 'monselect':
      $stmt = $get['con']->prepare($get['conf']['Host']['MONSELECT']);
      $stmt->bindValue(':host_id'    ,$get['host_id'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result'] = !($stmt->rowCount());
      $rows = array();
      while($row = $stmt->fetch()){
        $rows[] = $row;
      }
      $res['list'] = json_encode($rows);
    break;

    case 'hostupdate':
      $stmt = $get['con']->prepare($get['conf']['Host']['HOSTUPDATE']);
      $stmt->bindValue(':checked',$get['checked'], PDO::PARAM_INT);
      $stmt->bindValue(':alert_id',$get['alert_id'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result']=0;
    break;

    case 'monupdate':
      $stmt = $get['con']->prepare($get['conf']['Host']['MONUPDATE']);
      $stmt->bindValue(':checked',$get['checked'], PDO::PARAM_INT);
      $stmt->bindValue(':alert_id',$get['alert_id'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result']=0;
    break;

    case 'hostdelete':
    break;

    case 'mondelete':
    break;
  }
  return $res;
}

?>
