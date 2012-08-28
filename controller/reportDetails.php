<?php
require_once('views/reportDetails.view.php');
require_once('views/error.view.php');
require_once('classes/reportDetails.php');

class ReportDetailsController {

public function execute() {

	if (isset($_GET['reportID'])) {

		$reportDetails = new ReportDetails($_GET['reportID']);
		$reportData = $reportDetails->getReportDetails();


		return new ReportDetailsView($reportData);

	} else {

		return new ErrorView();
	}


}


}

?>
