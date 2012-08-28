<?php
require_once('classes/user.php');
require_once('classes/bikePartService.php');

class ReportData {

	public $road;
	public $housenumber;
	public $datacode;
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

	private $id;
	public $creationDate;
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
		return 'report'.$this->id;
	}

	function getUniqueBikeID() {
              return 'bike'.$this->id;
        }


}

?>
