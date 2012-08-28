<?php
require_once('views/error.view.php');
require_once('config/config.php');

class VoidController {

	public function execute() {
		// TODO use get or post?
		if (!isset($_REQUEST["token"])) {
			return new ErrorView();
		}

		$token = $_REQUEST["token"];

		if ($token != CRON_JOB_TOKEN) {
			return new ErrorView();
		}

		// We are sane
		// Are we? Yes we are!
		$voidService = new VoidService();
		$voidService->updateVoid();
		
		
		require_once('views/empty.view.php');
		return new EmptyView();
		

	}

}

?>
