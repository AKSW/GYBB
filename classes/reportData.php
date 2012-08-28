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

	public $dateOfTheft;
	public $lastSeen;
	public $noticedTheft;
	public $registrationNumber;
	public $policeStation;

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
		$this->creationDate = date_format(date_create(), 'Y-m-d');
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
