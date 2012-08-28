<?php
    

	class ReportData {
		
		//Erstellungsdatum
		public $creationDate;
		
		//Typ des Bikes, see BikeTypes
		public $biketype;
		
		//Farbe als String
		public $color;
		
		//Datum des Diebstahls
		public $dateoftheft;
		
		//Point des Diebstahls
		public $placeoftheft;
		
		//Beschreibung des Fahrrades
		public $bikeDescription;
		
		//Array mit Komponenten
		public $components;
		
		// Preis (in EUR)
		public $price;
		
		//Hersteller
		public $manufacturer;
		
		//Laufradgrösse in Zoll
		public $size;
		
		//Registriernummer
		public $codednumber;
		
		//Rahmennummer
		public $framenumber;
		
		//Die Polizeistation
		public $policeIRI;
		
		
		//Der Benutzer der den Report angelegt hat
		public $user;
		
                private $id;    

		function __construct() {
                        require_once 'classes/user.php';
                	$this->id = date('YmdHis-') . User::getCurrentUser()->getHash();
		}                
                
                
                
                function uniqueId() {
                    return $this->id;
                }
                
                
		
}

?>