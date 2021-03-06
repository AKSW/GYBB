<?php
require_once('views/report.view.php');
require_once('views/reportSuccess.view.php');
require_once('classes/reportData.php');
require_once('classes/reportRepository.php');
require_once('classes/reportService.php');
require_once('classes/user.php');
require_once('classes/validator.php');
require_once('classes/fileChecker.php');

class ReportController {

	public function execute() {

		if ($_SERVER['REQUEST_METHOD'] == "GET") {

			return new ReportView();

		} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

			// validate post data and create the values in the reportdata
			$validator = new Validator($_POST);
			$filecheck = new FileChecker($_FILES);

			$cleanData = $validator->getValidatedData();

			$bikeImages = array();
			try {
				$bikeImages = $filecheck->checkFileErrors();
			} catch (Exception $e) {
				// TODO do sth. with the error-message, but dont echo it
				// echo $e;
			}

			$service = new ReportService();
			$reportID = $service->saveNewReport($cleanData, $bikeImages);

			if ($reportID) {
				return new ReportSuccessView($reportID);
			} else {
				return new ReportView();
			}

		} else  {
			return new ErrorView();
		}

	}

}

?>
