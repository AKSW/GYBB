<?php
	require_once 'views/home.view.php';

	class HomeController {

	
		public function execute() {
			$view = new HomeView();
			return $view;
		
		}
	

	}


?>


