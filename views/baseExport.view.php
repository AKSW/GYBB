<?php

abstract class BaseExportView {

	public function show() {
		$this->doShow();
	}

	abstract protected function doShow();

}

?>
