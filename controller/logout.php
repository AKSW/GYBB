<?php
		require_once 'views/home.view.php';
	

	class LogoutController {
		
		public function execute() {
			session_destroy();
			$view = new HomeView();
			return $view;
		}
		
	}


?>