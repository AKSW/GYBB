<?php


/**
 * Eine Komponente eines Bikes. 
 * Jede Komponente gehoert zu einem Typ.
 *
 * @author ju
 */
class BikePart {
  
  
  
  
}

/** OWL Klasse der Art des Bikeparts */
class BikePartType {
    //Anzeige name
    //RDFS label
    public $label;
    
    public $uri;
    
    function __construct($label) {
      $this->label = $label;
      require_once 'classes/utils.php';
      $this->uri = urlencode(firstCharToUpper($label));
        
    } 
    

  
}



?>
