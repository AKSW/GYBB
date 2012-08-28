<?php
require_once('views/baseExport.view.php');
require_once('classes/utils.php');

class JsonView extends BaseExportView {

	private $data;

	function __construct($data) {
		$this->data = $data;
	}

	protected function doShow() {
		// just return the data as a json object -- the rest will be done in js
		foreach ($this->data as $singleKey => $singleValue) {
			// if we have a multiple array look for date values and beautify them
			if (is_array($singleValue)) {
				foreach ($singleValue as $key => $value) {
					if ($key === 'noticedTheft' || $key === 'created' || $key === 'lastSeen' || $key === 'hintWhen') {
						$this->data[$singleKey][$key] = readableDateTime($value);
					}
				}
			}
		}
		echo json_encode($this->data);
	}
}

?>


