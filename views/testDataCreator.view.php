<?php

class TestDataCreatorView {

	public function show() {
		require('templates/header.php');
		$this->doShow();
		require('templates/footer.php');
	}

	protected function doShow() {
		echo '<h1>Testdata successfully created.</h1>';
	}

}

?>

