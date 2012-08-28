	<?php


/**
 * Aktualisiert die Statistikdaten.
 *
 * Basiert auf den Sparql Queries von Void Impl
 * @link http://code.google.com/p/void-impl/wiki/SPARQLQueriesForStatistics
 */
class VoidService {

	// Count of triples
	private $statQueries = array(
				"triples" => "SELECT (COUNT(*) AS ?no) { ?s ?p ?o  }",
		"entities" =>	"SELECT COUNT(distinct ?s) AS ?no { ?s a []  }",
		"classes" => "SELECT COUNT(distinct ?o) AS ?no { ?s rdf:type ?o }", //number of classes
		"properties" => "SELECT count(distinct ?p) { ?s ?p ?o }",
		"distinctSubjects" => "SELECT (COUNT(DISTINCT ?s ) AS ?no) {  ?s ?p ?o   } ",
		"distinctObjects" => "SELECT (COUNT(DISTINCT ?o ) AS ?no) {  ?s ?p ?o  filter(!isLiteral(?o)) }"
	);

	private $queryFactory;
	private $curlHelper;

	private $results;
	
	function __construct() {
		$this->queryFactory = new QueryFactory();
		$this->curlHelper = new CurlHelper();
		$this->results = array();
	}


	public function updateVoid() {
		foreach ($this->statQueries as $predicate => $query) {
			$this->updateVoidStatistic($query, $predicate);
		}

	}

	private function updateVoidStatistic($query, $predicate) {
		
		$result = $this->curlHelper->getSparqlResults($query);

		if ($result == null) {
			throw new NoStatisticsException($query);
		}
		
		$noObj = $result[0];
		
		$number = intval($noObj->no->value);

		$qf = $this->queryFactory;

		$updateStmt = $qf->fullPrefixList;
		/*$updateStmt .= "WITH  \n"
			. " DELETE  FROM <" . VOID_GRAPH . ">   { gybb:GetYourBikeBack void:" . $predicate . " ?o . ?s ?p ?o } \n "
			. " INSERT {gybb:GetYourBikeBack ?p " . $number . "}\n "
			. "WHERE { gybb:GetYourBikeBack void:" . $predicate . " ?o} ";
		*/
		
		$updateStmt .= "\nMODIFY <" . VOID_GRAPH . ">" 
				. " DELETE { gybb:GetYourBikeBack void:" . $predicate . "  ?o } \n"
				. " INSERT { gybb:GetYourBikeBack void:" . $predicate . " " . $number . " }"
				. " WHERE { gybb:GetYourBikeBack void:" . $predicate . " ?o . ?s ?p ?o .}";
				
		
		$updateResult = $qf->execSparql($updateStmt);
		
		$this->results[] = $qf->fetchResult($updateResult);
		
		
		
		
	}

}



?>
