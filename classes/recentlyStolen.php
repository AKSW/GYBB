<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');
require_once('classes/utils.php');
require_once('classes/bike.php');

/**
 *
 **/
class RecentlyStolen {

	private $limit;

	function __construct($limit = false) {
		$this->limit = $limit;
	}

	public function getRecentlyStolenReports() {
		$curl = new CurlHelper();

		$query = $this->buildSparqlQuery();
		$allReports = $curl->getSparqlResults($query);

		$recently = cleanupSparqlResults($allReports);

		// foreach bike, get the biketype
		foreach ($recently as $key => $singleBike) {
			$bike = new Bike($singleBike['bikeID']);
			$recently[$key]['bikeType'] = $bike->getBikeType();
		}
		return $recently;
	}


	function buildSparqlQuery() {
		$sc = new SparqlConstants();

		$query = $sc->fullPrefixList . '
			SELECT * WHERE {
				?reportID gybbo:noticedTheft ?noticedTheft ;
									dct:created ?date ;
									gybbo:city ?city ;
									gybbo:postcode ?postcode ;
									gybbo:state ?state ;
									gybbo:describesTheftOf ?bikeID .
				?bikeID gybbo:color ?color .

		} ORDER BY DESC(?date)
		';
		if ($this->limit !== false) {
			$query .= ' LIMIT ' . (int) $this->limit;
		}

		return $query;
	}


}

?>
