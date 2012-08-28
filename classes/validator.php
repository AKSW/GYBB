<?php

/**
 * This class is used to validate and parse $_POST-data
 * send to the server, strip out useless chars etc and
 * prepare the data for db/endpoint usage
 **/
class Validator {

	// save cleaned stuff in here
	private $cleanPostData = array();


	function __construct($post) {
		$this->validate($post);
	}


	private function validate($post) {
		if (is_array($post)) {
			foreach($post as $name => $value) {
				// the post data can be an array for images and components
				// images will be handled by the ImageHandler-Class
				if (is_array($value)) {
					$tempArray = array();
					foreach ($value as $key => $subValue) {
						$tempArray[$key] = $this->cleanUp($subValue);
					}
					$this->cleanPostData[$name] = $tempArray;
				} else {
					$this->cleanPostData[$name] = $this->cleanUp($value);
				}
			}
		}
	}


	// removes everything but the wanted chars and returns a clean string
	public function cleanUp($string) {
		$string = preg_replace('/[^A-Za-zäöüßÄÜÖé\-_0-9:\s\.,@!\?]/i', '', $string);
		return trim($string);
	}


	public function getValidatedData() {
		return $this->cleanPostData;
	}


}

?>
