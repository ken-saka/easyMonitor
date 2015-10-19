<?php

/* ---------------------------------------------
input 

 $get
  conf
  con
  action
  hostname
  alertLevel_id
  alertContent
  cheked
  offset
  count
  

create
  occuerDate

output
  for DB
  $res
    result  : 0...ok, 1...ng
    list    : array


alertLevel_id
 0 ... success
 1 ... info
 2 ... warning
 3 ... danger
   ---------------------------------------------
*/

function alert($get){

  switch($get['action']){
    case 'insert':
      $stmt = $get['con']->prepare($get['conf']['Alert']['INSERT']);
      $stmt->bindValue(':hostname' ,    $get['hostname'], PDO::PARAM_STR);
      $stmt->bindValue(':alertLevel_id',$get['alertLevel_id'], PDO::PARAM_INT);
      $stmt->bindValue(':alertContent' ,$get['alertContent'], PDO::PARAM_STR);
      $stmt->execute();
      $res['result'];
    break;

    case 'select':
      $stmt = $get['con']->prepare($get['conf']['Alert']['SELECT']);
echo $get['conf']['Alert']['SELECT']."\n";
      $stmt->bindValue(':offset' , $get['offset'],  PDO::PARAM_INT);
      $stmt->bindValue(':count'  , $get['count'],   PDO::PARAM_INT);
      $stmt->execute();
      $res['result'] = !($stmt->rowCount());
      $res['list'] = array();
      while($row = $stmt->fetch()){
        $res['list'] += array($row);
      }
    break;
    case 'delete':
    break;
    case 'update':
    break;
  }
  return $res;
}

?>
