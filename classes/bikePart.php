<?php


/**
 * Eine Komponente eines Bikes. 
 * Jede Komponente gehoert zu einem Typ.
 *
 * @author ju
 */
class BikePart {
  
  public $type;
  
  public $label;
  public $uri;
  
  function __construct($value, $type) {
    $this->type = $type;
    $this->label = $value;
	$this->uri = urlencode(firstCharToUpper($value));
  }
  
  
  
  
}

/** OWL Klasse der Art des Bikeparts */
class BikePartType {
    //Anzeige name
    //RDFS label
    public $label;
    
    //Owl Class
    public $class;
    
    //Owl Predicate
    public $predicate;
    
    function __construct($label) {
      $this->label = $label;
      require_once 'classes/utils.php';
      $this->class = urlencode(firstCharToUpper($label));
      $this->predicate = urlencode(firstCharToLower($label) );
    } 
    

  
}



?>
