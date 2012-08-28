<?php

/**
 * Labels for input fields are globally defined here
 **/
class Labels {

	private $labels = array(
		'road' => 'Streetname',
		'house_number' => 'Housenumber',
		'postcode' => 'ZIP code',
		'city' => 'City',
		'lon' => 'Longitude',
		'lat' => 'Latitude',
		'dateOfTheft' => 'Date of theft',
		'lastSeen' => "Last seen at ... o'clock",
		'noticedTheft' => "Noticed theft at ... o'clock",
		'bikeType' => 'Type of your bike',
		'color' => 'Color',
		'comment' => 'Description / more information',
		'price' => 'Price in €',
		'manufacturer' => 'Manufacturer',
		'wheelSize' => 'Wheelsize in inch',
		'frameNumber' => 'Framenumber',
		'registrationCode' => 'Police registered number (if any)',
		'policeStation' => 'Police station in charge'
	);



	public function getLabels() {
		return $this->labels;
	}

	public function getLabelsAsJSON() {
		return json_encode($this->labels);
	}

}

?>
