<?php
require_once('classes/utils.php');

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
		$this->uri = urlencode(ucfirst($value));
	}

}

/** OWL Klasse der Art des Bikeparts */
// TODO 2 classes in one file is not nice
class BikePartType {

	// RDFS label
	public $label;

	// Owl Class
	public $class;

	// Owl Predicate
	public $predicate;

	function __construct($label) {
		$this->label = $label;
		$this->class = urlencode(ucfirst($label));
		$this->predicate = urlencode(lcfirst($label) );
	}


}



?>
