<?php

class UserErrorView {

	public $exception = false;

	public function show() {
		require('templates/header.php');
		$this->doShow();
		require('templates/footer.php');
	}

	protected function doShow() {
		echo '<h2>You are not logged in. Please login via Facebook.</h2>';

		if ($this->exception && DEBUG) {
			echo "<pre>" . $this->exception->getMessage() . "</pre>";
		}
	}

}

?>
