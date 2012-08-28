<?php
require_once('views/help.view.php');


class HelpController {

	public function execute() {
		$view = new HelpView();
		return $view;
	}


}


?>

