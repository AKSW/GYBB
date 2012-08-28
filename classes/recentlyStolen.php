<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');
require_once('classes/utils.php');

/**
 *
 **/
class RecentlyStolen {

	public function getRecentlyStolenReports($limit) {
		$curl = new CurlHelper();

		$query = $this->buildSparqlQuery($limit);
		$allReports = $curl->getSparqlResults($query);

		return cleanupSparqlResults($allReports);
	}


	function buildSparqlQuery($limit) {
		$sc = new SparqlConstants();

		$query = $sc->fullPrefixList . '
			SELECT * WHERE {
				?reportID gybbo:noticedTheft ?noticedTheft ;
									dct:created ?date ;
									gybbo:city ?city ;
									gybbo:describesTheftOf ?bikeID .
				?bikeID gybbo:bikeType ?bikeType ;
								gybbo:color ?color
			} ORDER BY DESC(?date) LIMIT ' . (int) $limit;

		return $query;
	}


}

?>
