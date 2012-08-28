<?php


/**
 * Aktualisiert die Statistikdaten.
 *
 * Basiert auf den Sparql Queries von Void Impl
 * @link http://code.google.com/p/void-impl/wiki/SPARQLQueriesForStatistics
 */
class StatisticsService {

	const REPORTS_WITH_CREATION_DATE = 'SELECT (count(?s) as ?count) ?date  WHERE {?s <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://demo.aksw.org/ontology/Report> . ?s <http://purl.org/dc/terms/created> ?date }';


	private $queryFactory;
	private $curlHelper;

	private $now;



	function __construct() {
		$this->queryFactory = new QueryFactory();
		$this->curlHelper = new CurlHelper();
		$this->now = time();
	}

	public function updateStatistics() {
		$this->updateReportsWithCreationDate();
	}

	private function updateReportsWithCreationDate() {
		$results = $this->curlHelper->getSparqlResults(StatisticsService::REPORTS_WITH_CREATION_DATE);

		if ($results == null) {
			throw new NoStatisticsException(StatisticsService::REPORTS_WITH_CREATION_DATE);
		}

		$dataSet = new OneDimensionalCountingDataSet("reportsDef", "reports" );





		$lastDay = $this->now - STATISTICS_HISTORY_DAYS * (24 * 60 * 60);
		foreach ($results as $result) {

			$dayTime = $this->parseAndTruncateDateValue($result->date);


			if ($dayTime >= $lastDay) {
				$count = $this->parseIntValue($result->count);
				$dayKey = intval(date("Ymd", $dayTime));//calculate date key
				$dataSet->addObservation($dayKey, $count);

			}
		}

		$this->deleteDataSet($dataSet);
		//create dataset
		$sparqlBuilder = new SparqlBuilder(DATACUBE_GRAPH);
		$sparqlBuilder->subject("gybbstat", $dataSet->name);
		$dataSetPredicates = array();
		$dataSetPredicates[] =  predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, SparqlConstants::QB, "DataSet");
		$dataSetPredicates[] =  predicateUri(SparqlConstants::QB, "structure", SparqlConstants::GYBBSTAT, $dataSet->structure);
		$dataSetPredicates[] = predicateLiteral(SparqlConstants::RDFS, SparqlConstants::COMMENT, "Reports by day daily snapshot");
		$dataSetPredicates[] = predicateLiteral(SparqlConstants::RDFS, SparqlConstants::LABEL, "Reports by day");
		$dataSetPredicates[] = typedLiteral(SparqlConstants::DCT, SparqlConstants::CREATED, $dataSet->creationTime, SparqlConstants::XSD_DATETIME);
		$sparqlBuilder->predicates($dataSetPredicates);

		$this->queryFactory->execSparql($sparqlBuilder->toSparql(), DATACUBE_GRAPH);


		foreach ($dataSet->observations as $key => $value) {

			$observationName = "obs-" . $dataSet->name . "-" . $key;
			$sparqlBuilder = new SparqlBuilder(DATACUBE_GRAPH);
			$sparqlBuilder->subject("gybbstat", $observationName);
			$observationPredicates = array();
			$observationPredicates[] = predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, SparqlConstants::QB, "Observation");
			$observationPredicates[] = predicateUri(SparqlConstants::QB, "dataSet", SparqlConstants::GYBBSTAT, $dataSet->name);
			$observationPredicates[] = typedLiteral(SparqlConstants::GYBBSTAT, "count", $value, SparqlConstants::XSD_INT);


			$observationPredicates[] = typedLiteral(SparqlConstants::GYBBSTAT, "refPeriod", $this->dateKeyToString($key), SparqlConstants::XSD_DATE);
			$sparqlBuilder->predicates($observationPredicates);

			$this->queryFactory->execSparql($sparqlBuilder->toSparql(), DATACUBE_GRAPH);

		}

	}

	private function dateKeyToString($key) {
		$raw = "" . $key;
		return substr($raw, 0, 4) . "-" . substr($raw, 4, 2) . "-" . substr($raw, 6);
	}

	/**
	 * Parses a json sparql date result
	 */
	private function parseAndTruncateDateValue($date) {
		$time = strtotime($date->value);
		return $time;

	}

	private function parseIntValue($value) {
		return intval($value->value);
	}

	public function deleteDataSet($dataSet) {
		$qf = $this->queryFactory;
		$deleteDataset = $qf->fullPrefixList;

		$deleteDataset .= " DELETE FROM <" . DATACUBE_GRAPH . "> { ?s ?p ?o } WHERE { ?s ?p ?o . FILTER( ?s = gybbstat:" . $dataSet->name . "  ) }";
		$qf->execSparql($deleteDataset);

		$deleteObservations = $qf->fullPrefixList;
		$deleteObservations .= " DELETE  FROM <" . DATACUBE_GRAPH . "> { ?s ?p ?o } "
				." WHERE {	?s ?p ?p . "
				." { SELECT ?s WHERE { ?s qb:dataSet  gybbstat:" . $dataSet->name . " . }}"
				. " } ";

		$qf->execSparql($deleteObservations);

	}


}


class DataSet {


	public $observations;
	public $creationTime;
	public $structure;
	public $name;



	function __construct($structure, $namePrefix) {
		$this->creationTime = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
		$this->name = $namePrefix . "-" . date('Ymd');
		$this->struture = $structure;
		$this->observations = array();
	}


	function ttl() {


	}

}

class OneDimensionalCountingDataSet extends DataSet {

	public function addObservation($key, $count) {
		if (array_key_exists($key, $this->observations)) {
					$this->observations[$key] = $this->observations[$key] + $count;
				} else {
					$this->observations[$key] =  $count;
				}
	}

}





?>
