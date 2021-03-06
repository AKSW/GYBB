<?php
require_once('classes/user.php');
require_once('classes/bikePartService.php');

class ReportData {

	public $road;
	public $house_number;
	public $postcode;
	public $city;
	public $lon;
	public $lat;

	public $lastSeen;
	public $noticedTheft;
	public $circumstances;
	public $registrationNumber;
	public $policeStation;
	public $findersFee;

	public $bikeType;
	public $color;
	public $comment;
	public $price;
	public $manufacturer;
	public $wheelSize;
	public $frameNumber;

	public $images;
	public $components;

	private $id;
	public $created;
	public $user;


	/**
		* Initializes a new Report Data.
		*/
	function initialize() {
		$this->id = date('YmdHis-') . User::getCurrentUser()->getHash();
		$this->created = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
		$this->user = User::getCurrentUser();
	}


	function getUniqueID() {
		return 'report' . $this->id;
	}


	function getUniqueBikeID() {
		return 'bike' . $this->id;
	}


}

?>
