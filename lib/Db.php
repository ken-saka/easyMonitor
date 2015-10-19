<?php

try {
  $con = new PDO(
     'mysql:host='.$conf["Db"]["DB_HOST"]
   .';port='      .$conf["Db"]["DB_PORT"]
   .';dbname='    .$conf["Db"]["DB_NAME"]
   .';charset='   .$conf["Db"]["DB_CHAR"]
   ,$conf["Db"]["DB_USER"]
   ,$conf["Db"]["DB_PASS"]
   ,array(PDO::ATTR_EMULATE_PREPARES => false)
  );
}
catch (PDOException $e){
  exit($e->getMessage());
}

?>
