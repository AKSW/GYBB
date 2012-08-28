<?php

require_once 'views/error.view.php';
require_once 'config/config.php';

class StatisticsController {
	
	public function execute() {
		if (!isset($_REQUEST("token")))  {
			return new ErrorView();
		}
		$token = $_REQUEST("token");
		if ($token != CRON_JOB_TOKEN) {
			return new ErrorView();
		}
		
		//We are sane
		
		
		
	}
	
	
}
?>
