<?php
require_once('views/userError.view.php');

class UserErrorController {

	public function execute() {
		return new UserErrorView();
	}

}


?>
