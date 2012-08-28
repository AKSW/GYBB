<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');
require_once('classes/utils.php');

/**
 *
 **/
class BikeImages {


	private $bikeID;
	private $limit;

	function __construct($bikeID, $limit)  {
		$this->bikeID = $bikeID;
		$this->limit = $limit;
	}

	public function getImages() {
		$curl = new CurlHelper();

		$query = $this->buildSparqlQuery();
		$images = $curl->getSparqlResults($query);
		return cleanupSparqlResults($images);
	}


	function buildSparqlQuery() {
		$sc = new SparqlConstants();

		$query = $sc->fullPrefixList . '
			SELECT ?image WHERE {
				<' . $sc->allPrefixes['gybb'] . $this->bikeID . '> ' . $sc::FOAF . ':' . $sc::DEPICTION . ' ?image
			} ';
		if ($this->limit !== false) $query .= 'LIMIT ' . $this->limit;
		return $query;
	}


}

?>

