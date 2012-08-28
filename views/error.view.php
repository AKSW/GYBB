<?php

	class ErrorView {

		public function show() {
			require('templates/header.php');

			$this->doShow();	
			require('templates/footer.php');	
			
		}

		protected function doShow() {
			print "<h2>Ups</h2>";
		}
	}

?>
