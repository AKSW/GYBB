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
		'lastSeen' => "Last seen at",
		'noticedTheft' => "Noticed theft at",
		'bikeType' => 'Type of your bike',
		'color' => 'Color',
		'comment' => 'Description / more information',
		'price' => 'Price in €',
		'manufacturer' => 'Manufacturer',
		'wheelSize' => 'Wheelsize in inch',
		'frameNumber' => 'Framenumber',
		'registrationCode' => 'Police registered number',
		'policeStation' => 'Police station in charge',
		'circumstances' => 'How it happened',
		'findersFee' => 'Finders fee',
		'hintWhen' => 'When did you see something?',
		'hintWhat' => 'What did you see?',
	);



	public function getLabels() {
		return $this->labels;
	}

	public function getLabelsAsJSON() {
		return json_encode($this->labels);
	}

}

?>
