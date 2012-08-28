<?php
require_once('classes/user.php');

class HintData {

	public $lon;
	public $lat;
	public $hintWhen;
	public $hintWhere;

	private $id;
	private $reportID;
	public $created;
	public $user;
	public $email;


	/**
		* Initializes a new Report Data.
		*/
	function initialize() {
		if (User::getCurrentUser()) {
			$this->id = date('YmdHis-') . User::getCurrentUser()->getHash();
			$this->user = User::getCurrentUser();
		} else {
      $this->id = sha1(time());
			$this->user = 'anonymous';
		}

		$this->created = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
		$this->reportID = $_POST['reportID'];
	}


	function getUniqueID() {
		return 'hint' . $this->id;
	}

	function getUniqueReportID() {
		return $this->reportID;
	}

}

?>
