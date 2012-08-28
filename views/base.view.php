<?php

	abstract class BaseView {

		public function show() {
			require('templates/header.php');

			$this->doShow();

			require('templates/footer.php');
		}

		abstract protected function doShow();

	}

?>
