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
      $stmt = $get['con']->prepare($get['conf']['Host']['INSERT']);
      $stmt->bindValue(':hostname'  ,$get['hostname'], PDO::PARAM_STR);
      $stmt->bindValue(':ipaddress' ,$get['ipaddress'], PDO::PARAM_STR);
      $stmt->execute();
      $res['result']=0;
      $host_id = $get['con']->lastInsertId();
      $res['list'] = $host_id;
    break;

    case 'hostselect':
      $stmt = $get['con']->prepare($get['conf']['Host']['SELECT']);
      $stmt->bindValue(':offset'   ,$get['offset'], PDO::PARAM_INT);
      $stmt->bindValue(':count'    ,$get['count'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result'] = !($stmt->rowCount());
      $rows = array();
      while($row = $stmt->fetch()){
        $rows[$row[0]] = array(
          'host_id'    => $row["host_id"]
         ,'hostname'   => $row["hostname"]
         ,'ipaddress'  => $row["ipaddress"]
        );
      }
      $res['list'] = json_encode($rows);
    break;

    case 'hostupdate':
      $stmt = $get['con']->prepare($get['conf']['Host']['UPDATE']);
      $stmt->bindValue(':checked',$get['checked'], PDO::PARAM_INT);
      $stmt->bindValue(':alert_id',$get['alert_id'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result']=0;
    break;

    case 'hostdelete':
      $stmt = $get['con']->prepare($get['conf']['Host']['DELETE']);
      $stmt->bindValue(':host_id',$get['host_id'], PDO::PARAM_INT);
      $stmt->execute();
      $res['result']=0;
      $res['list']=$get['host_id'];
    break;
  }
  return $res;
}

?>
