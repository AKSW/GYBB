<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'julian');
define('DB_PASSWORD', '8CUxDz7zGuA}$g');
define('DB_DATABASE', 'lgd');
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
$database = mysql_select_db(DB_DATABASE) or die(mysql_error());
?>
