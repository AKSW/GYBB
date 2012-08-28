<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bikePartRepository
 *
 * @author ju
 */
require_once 'classes/queryFactory.php';
class BikePartRepository extends QueryFactory {

	protected function toIriExpression($pre, $uri) {
		return $pre . ":" . $uri;
	}
	
	// Holt den BikePartType aus dem Repository legt ihn ggf. an.
	public function mergeBikePartType($bikePartType) {
		// 1. pruefe ob es den typ schon gibt, wenn ja gib den bikepartype zurueck
		//  2. wenn nicht anlegen

		if (!$this->existsBikePartType($this->toIriExpression(SparqlConstants::GYBBO , $bikePartType->class))) {
			// build bike part ontology
			//build class
			$sBuilder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
			$sBuilder->subject(SparqlConstants::GYBBO, $bikePartType->class);
			$classPredicates  = array();
			$classPredicates[] = predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, SparqlConstants::OWL, SparqlConstants::OWL_CLASS);
			$classPredicates[] = predicateUri(SparqlConstants::RDFS, SparqlConstants::RDFS_SUBCLASS, SparqlConstants::GYBBO, SparqlConstants::BIKEFACT);
			$classPredicates[] = predicateLiteral(SparqlConstants::RDFS, SparqlConstants::RDFS_LABEL, $bikePartType->label);

			$sBuilder->predicates($classPredicates);
			$this->execSparql($sBuilder->toSparql());

			$sBuilder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
			$sBuilder->subject(SparqlConstants::GYBBO, $bikePartType->predicate);
			$predicatePredicates = array();
			$predicatePredicates[] = predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, SparqlConstants::RDFS, SparqlConstants::RDFS_PROPERTY);
			$predicatePredicates[] = predicateUri(SparqlConstants::RDFS, SparqlConstants::RDFS_SUBPROP, SparqlConstants::GYBBO, SparqlConstants::BIKEFACTREL);
			$sBuilder->predicates($predicatePredicates);
			$this->execSparql($sBuilder->toSparql());

		}

	}
	
	/**
	 * Speichert die BikeParts 
	 * @param type $bikePart 
	 */
	public function mergeBikePart($bikePart) {
		$bikePartType = $bikePart->type;
		
		if(!$this->existsBikePartWithType(
				$this->toIriExpression(parent::GYBB, $bikePart->uri),
				$this->toIriExpression(parent::GYBBO, $bikePartType->class))) {
			
			$pBuilder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
			$pBuilder->subject(parent::GYBB, $bikePart->uri);
			$predicates = array();
			$predicates[] = predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, SparqlConstants::OWL, SparqlConstants::OWL_CLASS);
			$predicates[] = predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, parent::GYBBO, $bikePartType->class);
			$predicates[] = predicateLiteral(SparqlConstants::RDFS, SparqlConstants::RDFS_LABEL, $bikePart->label);
			$pBuilder->predicates($predicates);
			$this->execSparql($pBuilder->toSparql());

		}
		
	}


	private function existsBikePartType($typeUri) {
		$query = SparqlConstants::PREFIXES;
		$query .="
SELECT COUNT DISTINCT ?s WHERE {
?s rdf:type owl:ObjectProperty .
FILTER( ?s = " . $typeUri . ")
}";
	 return $this->exists($query);
		
	}

	private function existsBikePartWithType($partUri, $typeUri) {
		$query = parent::PREFIXES;
		$query .="
SELECT COUNT DISTINCT ?s, ?o WHERE {
?s rdf:type ?o .
FILTER( ?s = " . $partUri .  " && ?o = ". $typeUri . ")
}";
		return $this->exists($query);
	}
	
	

	protected function exists($countQuery) {
		$result = $this->execSparql($countQuery);

		$resultArray = $this->fetchResult($result);
		if (!$resultArray) {
			throw new RepositoryException($this->getLastError());
		}

		$value = array_values($resultArray);
		//print_r($value);
		return is_int($value[0]) && $value[0] > 0;
	}
	

}

?>
