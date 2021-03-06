<?php

class ErrorView {

	public $exception = false;

	public function show() {
		require('templates/header.php');
		$this->doShow();
		require('templates/footer.php');
	}

	protected function doShow() {
		echo '<h2>An error occured. Please contact the admin.</h2>';

		if ($this->exception && DEBUG) {
			echo "<pre>" . $this->exception->getMessage() . "</pre>";

		}
	}

}

?>
