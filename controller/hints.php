<?php
require_once('classes/hints.php');
require_once('views/json.view.php');
require_once('views/error.view.php');



class HintsController {

	public function execute() {
		if (isset($_GET) && isset($_GET['reportID'])) {
			$hints = new Hints($_GET['reportID']);
		} else {
			$hints = new Hints(false);
		}
		$hintList = $hints->getHints();

		return new JsonView($hintList);
	}

}

?>
