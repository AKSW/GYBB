<?php
require_once('config/config.php');
require_once('views/reportDetails.view.php');
require_once('views/error.view.php');
require_once('classes/reportDetails.php');
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');

class ReportDetailsController {

public function execute() {

	if (isset($_GET['reportID'])) {

		$singleReportData = array(); // the array we will give to the view
		$constants = new SparqlConstants();
		$reportDetails = new ReportDetails($_GET['reportID']);
		$reportData = $reportDetails->getReportDetails();

		foreach ($constants->allPrefixes as $short => $uri) {
			// strip out all uris -- then we have the field-id
			foreach ($reportData as $predObj) {
				if (strpos($predObj->pred->value, $uri) !== false) {
					$shortPred = str_replace($uri, '', $predObj->pred->value);
					$singleReportData[$shortPred] = $predObj->obj->value;
				}
			}
		}

		return new ReportDetailsView($singleReportData);

	} else {

		return new ErrorView();
	}


}


}

?>
