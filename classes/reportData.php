<?php
require_once 'classes/user.php';

class ReportData {

	public $road;
	public $housenumber;
	public $postcode
	public $city;
	public $lon;
	public $lat;

	public $dateoftheft;
	public $timestart;
	public $timeend;
	public $codednumber;
	public $police;
	// public $policeIRI; ???

	public $biketype;
	public $color;
	public $description;
	public $price;
	public $manufacturer;
	public $size;
	public $framenumber;

	public $images;
	public $components;

	public $user;
	public $creationDate;

	private $id;


	function __construct() {
		$this->id = date('YmdHis-') . User::getCurrentUser()->getHash();
	}

	function uniqueId() {
		return $this->id;
	}

}

?>
