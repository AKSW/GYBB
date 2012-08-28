<?php
require_once('classes/hints.php');
require_once('views/hintList.view.php');
require_once('views/error.view.php');


class HintListController {

	public function execute() {

		// showing 1 million entries is almost like no limit
		$hints = new Hints(false);
		$hintList = $hints->getHints();

		$view = new HintListView($hintList);
		return $view;
	}

}


?>


