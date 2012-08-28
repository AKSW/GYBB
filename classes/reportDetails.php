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
		$this->bikeID = str_replace('report', 'bike', $this->reportID);
	}


	public function getReportDetails() {
		$preparedReportData = array();
		$sc = new SparqlConstants();
		$curl = new CurlHelper();

		$query = $this->buildSparqlQuery();
		$reportData = $curl->getSparqlResults($query);

		foreach ($sc->allPrefixes as $short => $uri) {
			// strip out all uris -- then we have the field-id
			foreach ($reportData as $predObj) {
				if (strpos($predObj->pred->value, $uri) !== false) {
					$shortPred = str_replace($uri, '', $predObj->pred->value);
					// create an array for images if there are any
					if ($shortPred === 'depiction') {
						$preparedReportData[$shortPred][] = $predObj->obj->value;
					} else {
						$preparedReportData[$shortPred] = $predObj->obj->value;
					}
				}
			}
		}

		// now get all related bikeparts
		$query = $this->buildBikePartsQuery();
		$bikePartData = $curl->getSparqlResults($query);
		if (is_array($bikePartData) && !empty($bikePartData)) {
			foreach ($bikePartData as $part) {
				$preparedReportData['bikeparts'][] = array('type' => $part->type->value, 'name' => $part->name->value);
			}
		}

		return $preparedReportData;
	}


	function buildSparqlQuery() {
		$sc = new SparqlConstants();
		$sparql = 'SELECT * WHERE {
			{ <' . $sc->allPrefixes['gybb'] . $this->reportID . '> ?pred ?obj }
				UNION
			{ <' . $sc->allPrefixes['gybb'] . $this->bikeID . '> ?pred ?obj }
		}';

		return $sparql;
	}


	function buildBikePartsQuery() {
		$sc = new SparqlConstants();
		$sparql = $sc->fullPrefixList;
		$sparql .= '
			SELECT ?type ?name WHERE {
				<' . $sc->allPrefixes['gybb'] . $this->bikeID . '> ?parttype ?partname .
				?partname ' . $sc::RDFS . ':' . $sc::RDFS_LABEL . ' ?name .
				?parttype ' . $sc::RDFS . ':' . $sc::RDFS_LABEL . ' ?type . {
					SELECT * WHERE {
						?parttype ?p ' . $sc::GYBBO . ':' . $sc::BIKEFACTREL . ' .
					}
				}
			}';

		return $sparql;
	}



}

?>
