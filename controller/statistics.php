<?php
require_once('views/error.view.php');
require_once('config/config.php');

class StatisticsController {

	public function execute() {
		if (!isset($_REQUEST["token"])) {
			return new ErrorView();
		}

		$token = $_REQUEST["token"];

		if ($token != CRON_JOB_TOKEN) {
			return new ErrorView();
		}

		$view;
		try {
			$statisticsService = new StatisticsService();
			$statisticsService->updateStatistics();

			require_once('views/empty.view.php');
			$view =  new EmptyView();
		} catch (Exception $e) {
			$view = new ErrorView();
			$view->exception = $e;


		}
		return $view;



	}

}

?>
