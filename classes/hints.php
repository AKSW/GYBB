<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');

/**
 *
 **/
class Hints {

	private $reportID;
	private $limit;

	function __construct($reportID, $limit = false) {
		$this->reportID = $reportID;
		$this->limit = $limit;

	}


	public function getHints() {
		$finalResults = array();
		$constants = new SparqlConstants();
		$curl = new CurlHelper();

		$query = $this->buildSparqlQuery();
		$allHints = $curl->getSparqlResults($query);

		foreach ($allHints as $hint) {
			$tempArray = array();
			foreach ($hint as $key => $results) {

				// cycle through all uris that may be stripped from the value
				foreach ($constants->allPrefixes as $uri) {
					if (strpos($results->value, $uri) !== false) {
						$value = str_replace($uri, '', $results->value);
						$tempArray[$key] = $value;
					}
				}
				// if there was no uri to strip, use the normal value
				if (!isset($tempArray[$key])) {
          $tempArray[$key] = $results->value;
				}
			}

			$finalResults[] = $tempArray;
		}

		return $finalResults;
	}


	function buildSparqlQuery() {
		$sc = new SparqlConstants();

		$query = $sc->fullPrefixList . ' SELECT * WHERE { ';
		if ($this->reportID !== false) {
			$query .= '?hintID gybbo:hintFor <http://getyourbikeback.webgefrickel.de/resource/' . $this->reportID . '> . ';
		} else {
			$query .= '?hintID gybbo:hintFor ?reportID . ';
		}
		$query .= '
			?hintID geo:lon ?lon ;
						dct:created ?created ;
						geo:lat ?lat ;
						gybbo:hintWhen ?hintWhen ;
						dc:creator ?hintUser ;
						gybbo:hintWhat ?hintWhat .
			} ORDER BY DESC(?created) ';
		if ($this->limit !== false) {
			$query .= ' LIMIT ' . $this->limit;
		}

		return $query;
	}


}

?>

