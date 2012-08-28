<?php
require_once('classes/autoloader.php');
require_once('config/config.php');
require_once('classes/sparqlConstants.php');
require_once('classes/user.php');
require_once('views/error.view.php');
require_once('views/reportUpdated.view.php');

class UpdateReportController {

	public function execute() {

		$sc = new SparqlConstants();
		$qf = new QueryFactory();
		$ch = new CurlHelper();
		$pre = $sc->fullPrefixList;
		$curUser = User::getCurrentUser();

		// TODO must have -- check if the user is the owner of the report
		// before changing anything

		if (isset($_POST['reportID']) && isset($_POST['state'])) {
			$state = $_POST['state'];
			$reportID = $_POST['reportID'];
			$bikeID = str_replace('report', 'bike', $reportID);

			// only update the state if it is one of the following
			if ($state === 'open' || $state === 'resolved' || $state === 'closed') {

				$qf->execSparql($pre . '
					MODIFY <' . RESOURCE_GRAPH . '>
					DELETE { gybb:' . $reportID . ' gybbo:state ?deleteStatus }
					INSERT { gybb:' . $reportID . ' gybbo:state "' . $state . '"^^xsd:string }
					WHERE { gybb:' . $reportID . ' gybbo:state ?deleteStatus }
				');

				return new ReportUpdatedView($reportID);
			} else {
				return new ErrorView();
			}

		}
	}
}


?>

