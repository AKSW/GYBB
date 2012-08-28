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
  
    
    // Holt den BikePartType aus dem Repository
    //legt ihn ggf. an.
    public function mergeBikePartType($bikePartType) {
       // 1. pruefe ob es den typ schon gibt, wenn ja gib den bikepartype zurueck
      //  2. wenn nicht anlegen
      
    }
    
    
    private function existsBikePart($typeUri) {
       $query = SparqlConstants::PREFIXES;
       $query .= 
       " SELECT DISTINCT ?p WHERE { " . " }";
       
    }

  
  
}

?>
