<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');

/**
 *
 **/
class HintsByUser {

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

		$currentUser = User::getCurrentUser();
		$name = $currentUser->name;
		$email = $currentUser->email;

		$query = $sc->fullPrefixList . ' SELECT * WHERE { ';
		$query .= '?hintID gybbo:hintFor ?reportID . ';
		$query .= '
			?hintID geo:lon ?lon ;
						dct:created ?created ;
						geo:lat ?lat ;
						gybbo:hintWhen ?hintWhen ;
						dc:creator "' . $name . '"^^xsd:string ;
						foaf:mbox "' . $email . '"^^xsd:string ;
						gybbo:hintWhat ?hintWhat .
			} ORDER BY DESC(?created) ';

		return $query;
	}


}

?>

