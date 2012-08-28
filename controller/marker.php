<?php
require_once "views/marker.view.php";


	class MarkerController {
		
			public function execute() {
			$view = new MarkerView();
			return $view;
			}
		
		
	}


?>