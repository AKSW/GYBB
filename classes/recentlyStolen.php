<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');
require_once('classes/utils.php');
require_once('classes/bike.php');

/**
 *
 **/
class RecentlyStolen {

	public function getRecentlyStolenReports($limit) {
		$curl = new CurlHelper();

		$query = $this->buildSparqlQuery($limit);
		$allReports = $curl->getSparqlResults($query);

		$recently = cleanupSparqlResults($allReports);

		// foreach bike, get the biketype
		foreach ($recently as $key => $singleBike) {
			$bike = new Bike($singleBike['bikeID']);
			$recently[$key]['bikeType'] = $bike->getBikeType();
		}
		return $recently;
	}


	function buildSparqlQuery($limit) {
		$sc = new SparqlConstants();

		$query = $sc->fullPrefixList . '
			SELECT * WHERE {
				?reportID gybbo:noticedTheft ?noticedTheft ;
									dct:created ?date ;
									gybbo:city ?city ;
									gybbo:describesTheftOf ?bikeID .
				?bikeID gybbo:color ?color .

			} ORDER BY DESC(?date) LIMIT ' . (int) $limit;

		return $query;
	}


}

?>
