<?php
require_once('classes/suggestion.php');
require_once('views/json.view.php');
require_once('views/error.view.php');

/**
 *
 **/
class SuggestionController {

	public function execute() {
		if (isset($_GET['type']) && !empty($_GET['type']))  {
			$sg = new Suggestion($_GET['type'], $_GET['term']);
			$suggestions = $sg->getSuggestionList();
			$suggestionList = array();

			if ($suggestions !== false)  {
				foreach ($suggestions as $suggestion) {
					foreach ($suggestion as $stuff) {
						$suggestionList[] = $stuff->value;
					}
				}
				return new JsonView($suggestionList);
			} else  {
				return new ErrorView();
			}
		} else  {
			return new ErrorView();
		}
	}
}

?>
