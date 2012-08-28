<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SparqlConstants {

  const PREFIXES = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX contact: <http://www.w3.org/2000/10/swap/pim/contact#>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX dbp: <http://dbpedia.org/resource/>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbpedia: <http://dbpedia.org/>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
PREFIX gybb: <http://getyourbikeback.webgefrickel.de#>
PREFIX gybbo: <http://getyourbikeback.webgefrickel.de/ontology/>
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
PREFIX virtrdf: <http://www.openlinksw.com/schemas/virtrdf#> \n\n ";



  /** Präfix von gybb */
  const GYBB = "gybb";

  /** Präfix von dc */
  const DC = "dc";
  const XSD_DATE = "xsd:date";
  const XSD_DOUBLE = "xsd:double";
  const GEO = "geo";
  
  const GYBBO = "gybbo";
  const REPORT = "Report";
  const BIKEFACT = "BikeFact";
  const BIKEFACTREL = "BikeFactRelation";
  
  const RDF = "rdf";
  const RDF_TYPE = "type";
  
  const OWL = "owl";
  const OWL_CLASS = "Class";
  const RDFS_PROPERTY = "Property";
  const RDFS = "rdfs";
  const RDFS_LABEL = "label";
  const RDFS_SUBCLASS = "subClassOf";
  const RDFS_SUBPROP = "subPropertyOf";
  
   
  /**
   * Hilfsmethode für URIs
   */
  function ttl_uri($prefix, $value) {
    return (string) $prefix . ':' . (string) $value . ' ';
  }

  function ttl_literal($value) {
    return '"' . addcslashes((string) $value, "\n\r\t\"") . '"';
  }

}

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

  // Erstellt den SparqlBuilder $prefix und $suffix sind
  // fuer die eigentlichen Funktionen
  function __construct($prefix, $suffix) {
    $this->prefix = $prefix;
    $this->suffix = $suffix;
    $this->ttl = "";
    $this->header = SparqlConstants::PREFIXES;
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
