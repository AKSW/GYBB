<?php

function __autoload($class) {
 // ReportData oder BaseDao
 // macht dann ein include gegen classes/reportData.php bzw classes/dao/baseDao.php

	$filename = strtolower(substr($class,0,1)).substr($class, 1) . ".php";
	if (is_file('classes/'.$filename)) {
		include_once 'classes/'.$filename;
	}

}
?>
