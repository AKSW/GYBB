<?php

class RedirectView {

	private $target;

	function __construct($toUrl) {
		$this->target = $toUrl;
	}


	public function show() {
		header("Location: " . $this->target);
	}

}

?>
