<?php
require_once('views/reportdetail.view.php');
require_once('classes/reportdetail.php');

class ReportDetailController {

public function execute() {

	if (isset($_GET['reportID'])) {

	  $reportDetail = new ReportDetail($_GET['reportID']);
		$reportID = $reportDetail->getAllReportDetails();

		return new ReportDetailView($reportID);

	} else {

		return ErrorView();
	}


}


}

?>
