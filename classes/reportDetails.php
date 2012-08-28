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
		$preparedReportData = array();
		$constants = new SparqlConstants();
		$curl = new CurlHelper();

		$query = $this->buildSparqlQuery();
		$reportData = $curl->getSparqlResults($query);

		foreach ($constants->allPrefixes as $short => $uri) {
			// strip out all uris -- then we have the field-id
			foreach ($reportData as $predObj) {
				if (strpos($predObj->pred->value, $uri) !== false) {
					$shortPred = str_replace($uri, '', $predObj->pred->value);

					// create an array for images if there are any
					// NOTE TODO - use this for bikeparts too
					if ($shortPred === 'depiction') {
						$preparedReportData[$shortPred][] = $predObj->obj->value;
					} else {
						$preparedReportData[$shortPred] = $predObj->obj->value;
					}

				}
			}
		}
		return $preparedReportData;
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
