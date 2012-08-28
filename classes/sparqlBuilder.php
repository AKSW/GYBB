<?php
require_once('classes/sparqlConstants.php');

// TODO three functions 2 classes in one file. :-(

class PredicateBuilder extends SparqlConstants {

	private $predicate;
	private $pObject;

	function predicate($prefix, $value) {
		$this->predicate = (string) $this->ttl_uri($prefix, $value);
	}

	function literalObject($value) {
		$this->pObject = (string) $this->ttl_literal($value);
	}

	function uriObject($prefix, $value) {
		$this->pObject = (string) $this->ttl_uri($prefix, $value);
	}

	function objectType($type) {
		$this->pObject .= '^^' . (string) $type;
	}

	function ttl() {
		return (string) $this->predicate . ' ' . $this->pObject;
	}

}

function predicateUri($prefix, $value, $prefixObject, $valueObject) {
	$pred = new PredicateBuilder();
	$pred->predicate($prefix, $value);
	$pred->uriObject($prefixObject, $valueObject);
	return $pred;
}

// TODO type with xsd:string always?!
function predicateLiteral($prefix, $value, $literal) {
	$pred = new PredicateBuilder();
	$pred->predicate($prefix, $value);
	$pred->literalObject($literal);
	return $pred;
}

function typedLiteral($prefix, $value, $literal, $type) {
	$pred = predicateLiteral($prefix, $value, $literal);
	$pred->objectType($type);
	return $pred;
}

/**
 * Builder für TTL Syntax
 *
 */
class SparqlBuilder extends SparqlConstants {

	private $header;
	private $ttl;
	private $predicates;
	private $prefix;
	private $suffix;


	// Create a new sparqlBuilder, the graph value is where
	// the data will be written -- default is RESOURCE_GRAPH
	function __construct($graph = RESOURCE_GRAPH) {
		parent::__construct();

		$this->prefix =  "INSERT INTO <" . $graph . "> {\n";
		$this->suffix = "  } \n";
		$this->ttl = "";
		$this->header = $this->fullPrefixList;
	}

	function subject($prefix, $value) {
		if ($this->predicates) {
			$this->ttl .= " . \n";
		}
		$this->ttl .= $this->ttl_uri($prefix, $value);
		$this->predicates = false;
	}

	/**
	 * Setzt ein Array von Predicates
	 * @param type $predicates
	 * @return \TTLBuilder
	 */
	function predicates($predicates) {

		for ($i = 0; $i < count($predicates); $i++) {
			if ($i > 0 || $this->predicates) {
				$this->ttl .= " ; \n";
			}
			$this->predicates = true;
			$this->ttl .= ' ' . $predicates[$i]->ttl() . ' ';
		}
	}

	function toSparql() {
		return (string) $this->header . "\n"
			. $this->prefix . "\n"
			. $this->ttl . "\n"
			. $this->suffix;
	}

}

?>
