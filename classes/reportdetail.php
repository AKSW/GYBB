<?php

/**
 *
 **/
class ReportDetail {

	private $reportID;

	function __construct($reportID) {

		// TODO
		$this->reportID = $reportID;
		$this->getReportDetail();
	}


	function getReportDetail() {
		// TODO
	}

	public function getAllReportDetails() {
		return $this->reportID;



	}

}

?>
