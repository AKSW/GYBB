<?php

phpinfo();
ini_set("display_errors", "stdout");	
  $con=odbc_connect("VOS", "dba", "dba");
  print_r($con);
?>