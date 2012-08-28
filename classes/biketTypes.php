<?php


class BikeTypes {
	
	private $values;
	
	function __construct() {
		$this->values = array(
			"Mountainbike",
			"Trekkingbike",
			"Hollandrad"
		);
		
		
	}
	
	
	public function typesAsJson() {
		return json_encode($this->values); 
		
	}
	
}
