<?php
require_once('views/redirect.view.php');

class LogoutController {

	public function execute() {
		session_destroy();
		return new RedirectView('/index.php?action=home');
	}

}


?>
