<?php

class NoStatisticsException extends Exception {
	
	
	function __construct($query) {
		parent::_construct("Error executing query: " + $query);
	}
}

?>
