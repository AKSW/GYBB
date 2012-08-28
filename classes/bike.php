<?php
require_once('classes/utils.php');
require_once('classes/curlHelper.php');
require_once('classes/sparqlConstants.php');

/**
 * all bikedata except componentes + bikeparts
 *
 */
class Bike {

	private $bikeID;

	// when initializing this class, we need a bike ID
	function __construct($bikeID) {
		$this->bikeID = $bikeID;
	}


	function getBikeType() {
    $curl = new CurlHelper();

		$typeQuery = $this->buildTypeQuery();
		$typeData = $curl->getSparqlResults($typeQuery);

		$query = $this->buildBikeTypeQuery($typeData[0]->type->value);
		$bikeTypeData = $curl->getSparqlResults($query);

		return $bikeTypeData[0]->bikeType->value;
	}


	private function buildBikeTypeQuery($bikeClass) {
		$sc = new SparqlConstants();
		$sparql = 'SELECT ?bikeType WHERE {
			<' . $sc->allPrefixes['gybbo'] . $bikeClass . '> <' . $sc->allPrefixes['rdfs'] . 'label> ?bikeType .
		}';

		return $sparql;
	}


	private function buildTypeQuery() {
		$sc = new SparqlConstants();

		$sparql = 'SELECT ?type WHERE {
			<' . $sc->allPrefixes['gybb'] . $this->bikeID . '> <' . $sc->allPrefixes['rdf'] . 'type> ?type .
		}';

		return $sparql;
	}




}



?>

