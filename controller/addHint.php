<?php
require_once('views/hintSuccess.view.php');
require_once('classes/hints.php');
require_once('classes/hintData.php');
require_once('classes/hintRepository.php');
require_once('classes/hintService.php');
require_once('classes/validator.php');
require_once('classes/fileChecker.php');

class AddHintController {

	public function execute() {

		if ($_SERVER['REQUEST_METHOD'] == "POST") {

			// validate post data and create the values in the reportdata
			$validator = new Validator($_POST);
			$cleanData = $validator->getValidatedData();

			$service = new HintService();
			$hintID = $service->saveNewHint($cleanData);

			if ($hintID) {
				return new HintSuccessView($_POST['reportID']);
			} else {
				return new ErrorView();
			}

		} else  {
			return new ErrorView();
		}
	}


}

?>

