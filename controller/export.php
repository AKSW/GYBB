<?php
require_once('views/export.view.php');
require_once('views/error.view.php');
require_once('classes/curlHelper.php');
require_once('classes/sparqlConstants.php');

class ExportController {

	public function execute() {

		$curl = new CurlHelper();
		$sc = new SparqlConstants();
		$query = $sc->fullPrefixList;

		// we then return an export for the report/bikedata shown only
		// overwriting the query with the reportdetails query
		if (isset($_GET['reportID'])) {

			$reportID = $_GET['reportID'];
			$bikeID =  str_replace('report', 'bike', $_GET['reportID']);

			$query .= '
				CONSTRUCT { ?subject ?predicate ?object }
				WHERE {
					{
						?subject ?predicate ?object .
						gybb:' . $reportID . ' ?predicate ?object .
						FILTER REGEX (?subject, "' . $reportID . '") .
						FILTER NOT EXISTS { ?subject foaf:mbox ?object }
					}
					UNION
					{
						?subject ?predicate ?object .
						gybb:' . $bikeID . ' ?predicate ?object .
					}
					UNION
					{
						?subject ?predicate ?object .
						?subject gybbo:hintFor gybb:' . $reportID . ' .
						FILTER NOT EXISTS { ?subject foaf:mbox ?object }
					}
				}';

		} else {
			$query .= '
				CONSTRUCT { ?subject ?predicate ?object }
				WHERE { ?subject ?predicate ?object }
			';
		}

		$data = $curl->getExportData($query, $_GET['format']);

		if (!empty($data)) {
			return new ExportView($data, $_GET['format']);
		} else {
			return new ErrorView();
		}


	}

}


?>
