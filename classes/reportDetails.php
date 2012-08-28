<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');

/**
 *
 **/
class ReportDetails {

	private $reportID;
	private $bikeID;

	function __construct($reportID) {
		$this->reportID = $reportID;
	}


	public function getReportDetails() {
		$query = $this->buildSparqlQuery();

		$curl = new CurlHelper();
		return $curl->getSparqlResults($query);
	}


	function buildSparqlQuery() {
		$sc = new SparqlConstants();
		// bikeID == reportID
		$this->bikeID = str_replace('report', 'bike', $this->reportID);

		$reportDetailQuery = 'SELECT * WHERE {
			{ <' . $sc->allPrefixes['gybb'] . $this->reportID . '> ?pred ?obj }
				UNION
			{ <' . $sc->allPrefixes['gybb'] . $this->bikeID . '> ?pred ?obj }
		}';

		return $reportDetailQuery;
	}


}

?>
