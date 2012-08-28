<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SparqlConstants {
        const PREFIXES = "
PREFIX rdf: <http://wwww3org/1999/02/22-rdf-syntax-ns#> 
PREFIX contact: <http://wwww3org/2000/10/swap/pim/contact#> 
PREFIX dbo: <http://dbpediaorg/ontology/> 
PREFIX owl: <http://wwww3org/2002/07/owl#> 
PREFIX xsd: <http://wwww3org/2001/XMLSchema#> 
PREFIX rdfs: <http://wwww3org/2000/01/rdf-schema#> 
PREFIX rdf: <http://wwww3org/1999/02/22-rdf-syntax-ns#> 
PREFIX foaf: <http://xmlnscom/foaf/01/> 
PREFIX dc: <http://purlorg/dc/elements/11/> 
PREFIX dbp: <http://dbpediaorg/resource/> 
PREFIX dbpedia2: <http://dbpediaorg/property/> 
PREFIX dbpedia: <http://dbpediaorg/> 
PREFIX skos: <http://wwww3org/2004/02/skos/core#> 
PREFIX gybb: <http://getyourbikebackwebgefrickelde#>  
PREFIX geo: <http://wwww3org/2003/01/geo/wgs84_pos#> 
PREFIX virtrdf: <http://wwwopenlinkswcom/schemas/virtrdf#> \n\n ";
        

        
             /** Präfix von gybb */   
             const GYBB = "gybb";
             
             /** Präfix von dc */
             const DC = "dc";
    
             const XSD_DATE = "xsd:date";
             
               const XSD_DOUBLE = "xsd:double";
             
             const GEO = "geo";

                 /**
    *Hilfsmethode für URIs 
    */
    function ttl_uri($prefix, $value) {
        return (string)$prefix . ':' . (string)$value . ' ';
    }

    function ttl_literal($value) {
        return '"' . addcslashes((string)$value, "\n\r\t\"") . '"';
    }

  
}

class PredicateBuilder extends SparqlConstants {
    private $predicate;
    private $pObject;
    
    function predicate($prefix, $value) {
        $this->predicate =  (string)$this->ttl_uri($prefix, $value);
        
    }
    
    function literalObject($value) {
        $this->pObject =  (string)$this->ttl_literal($value);
        
    }
    function uriObject($prefix, $value) {
        $this->pObject =  (string)$this->ttl_uri($prefix, $value);
        
    }
    
    function objectType( $type) {
        $this->pObject .= '^^' . (string) $type;
    }
    
    
    function ttl() {
         return (string)$this->predicate . ' ' . $this->pObject;
    }
    
}


function predicateLiteral($prefix, $value, $literal) {
    $pred = new PredicateBuilder();
    $pred->predicate($prefix, $value);
    $pred->literalObject($literal);
    return $pred;
}


function typedLiteral($prefix, $value, $literal,  $type) {
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
        $this->ttl .=  $this->ttl_uri($prefix, $value);
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
      return  (string) $this->header . "\n" 
            . $this->prefix . "\n"
            . $this->ttl . "\n"
            . $this->suffix;
              
    }
    
    
}

?>
