<?php
require_once('classes/hintsByUser.php');
require_once('classes/reportsByUser.php');
require_once('views/me.view.php');
require_once('views/error.view.php');


class MeController {

	public function execute() {
		$loggedIn = (User::getCurrentUser()) ? true : false;
		if (!$loggedIn) {
			return new UserErrorView();
		} else {
			$hintsObject = new HintsByUser();
			$reportsObject = new ReportsByUser();

			$hints = $hintsObject->getHints();
			$reports = $reportsObject->getReports();

			return new MeView($reports, $hints);
		}

	}

}

?>
