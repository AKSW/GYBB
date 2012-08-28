<?php
require_once('classes/reportsInArea.php');
require_once('classes/reportDetails.php');
require_once('views/json.view.php');
require_once('views/error.view.php');


class ReportsInAreaController {

	public function execute() {
		if (isset($_GET['left']) && isset($_GET['right']) && isset($_GET['top']) && isset($_GET['bottom'])) {

			$ria = new ReportsInArea($_GET['left'], $_GET['right'], $_GET['top'], $_GET['bottom']);
			$allReports = $ria->getReportsInArea();

			return new JsonView($allReports);

		} else {

			return new ErrorView();
		}
	}

}


?>
