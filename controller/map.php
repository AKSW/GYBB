<?php
require_once "views/map.view.php";


	class MapController {
		
			public function execute() {
			$view = new MapView();
			return $view;
			}
		
		
	}


?>