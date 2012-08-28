<?php
require_once('views/reportDetails.view.php');
require_once('views/error.view.php');
require_once('classes/reportDetails.php');

class ReportDetailsController {

public function execute() {

	if (isset($_GET['reportID'])) {

		$reportDetails = new ReportDetails($_GET['reportID']);
		$reportData = $reportDetails->getReportDetails();

		$hints = new Hints($_GET['reportID']);
		$hintsData = $hints->getHints();

		return new ReportDetailsView($reportData, $hintsData);

	} else {

		return new ErrorView();
	}


}


}

?>
