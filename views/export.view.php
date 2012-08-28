<?php
require_once('views/baseExport.view.php');

class ExportView extends BaseExportView {

	private $data;
	private $format;

	function __construct($data, $format) {
		$this->data = $data;
		$this->format = $format;
	}

	protected function doShow() {
		header('Expires: 0');
		header('Content-Type: application/force-download');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Disposition: attachment; filename="export.' . $this->format . '"');
		header('Content-Transfer-Encoding: binary');
		echo $this->data;
	}
}

?>



