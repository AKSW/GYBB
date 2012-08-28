<?php


/**
 * Aktualisiert die Statistikdaten.
 * 
 * Basiert auf den Sparql Queries von Void Impl
 * @link http://code.google.com/p/void-impl/wiki/SPARQLQueriesForStatistics
 */
class StatisticsService {
	
	// Count of triples
	const STAT_QUERIES = 
			array(
				"triples" => "SELECT (COUNT(*) AS ?no) { ?s ?p ?o  }",
				"entities" =>	"SELECT COUNT(distinct ?s) AS ?no { ?s a []  }",	
				"classes" => "SELECT COUNT(distinct ?o) AS ?no { ?s rdf:type ?o }",//number of classes	
				"properties" => "SELECT count(distinct ?p) { ?s ?p ?o }",	
				"distinctSubjects" => "SELECT (COUNT(DISTINCT ?s ) AS ?no) {  ?s ?p ?o   } ",	
				"distinctObjects" => "SELECT (COUNT(DISTINCT ?o ) AS ?no) {  ?s ?p ?o  filter(!isLiteral(?o)) }"
			);
	
	private $queryFactory;
	
	private $curlHelper;
	
	function __construct() {
		$this->queryFactory = new QueryFactory();
		$this->curlHelper = new CurlHelper();
	}
	
	public function updateStatistics() {
		
	}
	
	private function updateVoid() {
		foreach (StatisticsService::STAT_QUERIES as $predicate => $query) {
			$this->updateVoidStatistic($query, $predicate);
		}
		
	}
	
	private function updateVoidStatistic($query, $predicate) {
		//TODO continue implementie
		$result = $this->curlHelper->getSparqlResults($query);
		
		//TODO extract ?no from result
		$number = 1000;
		
		$qf = $this->queryFactory;
		
		$updateStmt = $qf->fullPrefixList;
		$updateStmt .= "WITH <" . $qf->graphUri() . "> \n"
			. " DELETE { gybb:GetYourBikeBack ?p ?o } \n " 
			.	 " INSERT {gybb:GetYourBikeBack ?p " . $number . "}\n "
			. "WHERE { gybb:GetYourBikeBack void:" . $predicate . " ?o} ";
				
	}
	
}

?>
