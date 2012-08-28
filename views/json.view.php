<?php
require_once('views/baseExport.view.php');

class JsonView extends BaseExportView {

	private $data;

	function __construct($data) {
		$this->data = $data;
	}

	protected function doShow() {
		// just return the data as a json object -- the rest will be done in js
		echo json_encode($this->data);
	}
}

?>


