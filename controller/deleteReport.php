<?php
require_once('classes/autoloader.php');
require_once('config/config.php');
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');
require_once('classes/user.php');
require_once('views/error.view.php');
require_once('views/reportDeleted.view.php');

class DeleteReportController {

	public function execute() {

		$sc = new SparqlConstants();
		$qf = new QueryFactory();
		$ch = new CurlHelper();
		$pre = $sc->fullPrefixList;
		$curUser = User::getCurrentUser();

		// TODO must have -- check if the user is the owner of the report
		// before deleting anything
		//
		$reportID = false;
		if (isset($_POST['reportID'])) $reportID = $_POST['reportID'];
		if (isset($_GET['reportID'])) $reportID = $_GET['reportID'];

		if ($reportID !== false) {
			$bikeID = str_replace('report', 'bike', $reportID);

			// first delete all hints for the given report ID
			// we select all hints for the report id and delete them
			$hintQuery = 'SELECT ?hintID WHERE { ?hintID gybbo:hintFor gybb:' . $reportID . ' }';
			$hints = $ch->getSparqlResults($pre . $hintQuery);
			foreach ($hints as $hint) {
				$qf->execSparql($pre . '
					DELETE FROM <http://getyourbikeback.webgefrickel.de/> {
						<' . $hint->hintID->value . '> ?predicate ?object .
					} WHERE {
						<' . $hint->hintID->value . '> ?predicate ?object .
					}
				');
			}

			$qf->execSparql($pre . '
			DELETE FROM <http://getyourbikeback.webgefrickel.de/> {
					gybb:' . $reportID . ' ?predicate ?object .
				} WHERE {
					gybb:' . $reportID . ' ?predicate ?object .
				}
			');

			$qf->execSparql($pre . '
			DELETE FROM <http://getyourbikeback.webgefrickel.de/> {
					gybb:' . $bikeID . ' ?predicate ?object .
				} WHERE {
					gybb:' . $bikeID . ' ?predicate ?object .
				}
			');

			return new ReportDeletedView();

		} else {

      return new ErrorView();
		}

	// TODO delete all bike parts as well?

	}

}


?>
