<?php
require_once 'classes/sparq';

/**
 * Service zur Erstellung von Observations.
 *
 * @author ju
 */
class CubeService {


	private $queryFactory;
	private $curlHelper;

	const REPORTS_WITH_DATE = "SELECT ?s ?t WHERE {
 ?s <http://www.w3.org/1999/02/22-rdf-syntax-ns#type>  <http://getyourbikeback.webgefrickel.de/ontology/Report>  .
 ?s <http://purl.org/dc/terms/created> ?t }";

	function __construct() {
		$this->queryFactory = new QueryFactory();
		$this->curlHelper = new CurlHelper();
	}



	public function updateReportObservationsByDate() {
		$rawResult = $this->curlHelper->getSparqlResults(CubeService::REPORTS_WITH_DATE, DATACUBE_GRAPH);

		print_r ($rawResult);


	}



}

?>
