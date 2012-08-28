<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');

/**
 *
 **/
class ReportsByUser {

	private $left;
	private $right;
	private $top;
	private $bottom;

	public function getReports() {
		$finalResults = array();
		$constants = new SparqlConstants();
		$curl = new CurlHelper();

		$query = $this->buildSparqlQuery();
		$allReports = $curl->getSparqlResults($query);

		foreach ($allReports as $singleReport) {
			$singleTempArray = array();
			foreach ($singleReport as $key => $results) {

				// cycle through all uris that may be stripped from the value
				foreach ($constants->allPrefixes as $uri) {
					if (strpos($results->value, $uri) !== false) {
						$value = str_replace($uri, '', $results->value);
						$singleTempArray[$key] = $value;
					}
				}

				// if there was no uri to strip, use the normal value
				if (!isset($singleTempArray[$key])) {
          $singleTempArray[$key] = $results->value;
				}
			}

			$finalResults[] = $singleTempArray;
		}

		// foreach bike, get the biketype
		foreach ($finalResults as $key => $singleBike) {
			$bike = new Bike($singleBike['bikeID']);
			$finalResults[$key]['bikeType'] = $bike->getBikeType();
		}
		return $finalResults;
	}


	function buildSparqlQuery() {
		$sc = new SparqlConstants();

		$currentUser = User::getCurrentUser();
		$name = $currentUser->name;
		$email = $currentUser->email;

		$query = $sc->fullPrefixList . '
		SELECT * WHERE {
		?reportID geo:lon ?lon ;
							geo:lat ?lat ;
							gybbo:state ?state ;
							dc:creator "' . $name . '"^^xsd:string ;
							foaf:mbox "' . $email . '"^^xsd:string ;
							gybbo:noticedTheft ?noticedTheft ;
							dct:created ?date ;
							gybbo:city ?city ;
							gybbo:postcode ?postcode ;
							gybbo:describesTheftOf ?bikeID .

		?bikeID gybbo:color ?color .
		}
		';

		return $query;
	}


}

?>

