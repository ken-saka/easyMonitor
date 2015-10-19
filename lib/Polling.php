<?php

/* ---------------------------------------------------
input:
  $get
    case
    host
    port
    timeout
    arg

output:
  $res
    time : ok...deltatime , ng...$timeout
    code : ok...returnCode, ng...'NG'
   ----------------------------------------------------
*/

function polling($get) {

  $res;
  $ts = microtime(true);

  switch($get['case']){
  case 'http':
  case 'https':
    if($get['case'] == 'http'){
      $fsock = fsockopen(
                  $get['host']
                 ,$get['port']
                 ,$errno
                 ,$errstr
                 ,$get['timeout']
      );
    } else {
      $fsock = fsockopen(
                  'ssl://'.$get['host']
                 ,$get['port']
                 ,$errno
                 ,$errstr
                 ,$get['timeout']
      );
    }
    if(!$fsock){
      $res['time'] = $get['timeout'];
      $res['code'] = "NG";
    } else {
      fwrite($fsock, $get['arg']);
      $res['time'] = sprintf('%0.5f', microtime(true) - $ts);
      $res['code'] = fgets($fsock, 13);
    }  
  break;

  case 'ping':
    $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
    $socket  = socket_create(AF_INET, SOCK_RAW, 1);
    socket_set_option(
      $socket
     ,SOL_SOCKET
     ,SO_RCVTIMEO
     ,array('sec' => $get['timeout'], 'usec' => 0)
    );
    socket_connect($socket, $get['host'], null);

    $ts = microtime(true);
    socket_send($socket, $package, strLen($package), 0);
    if(socket_read($socket, 255)) {
      $res['time'] = sprintf('%0.5f', microtime(true) - $ts);
      $res['code'] = 'OK';
    } else {
      $res['time'] = $get['timeout'];
      $res['code'] = 'NG';
    }
    socket_close($socket);
  break;
  }
  return $res;
}

?>
