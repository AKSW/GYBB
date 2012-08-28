<?php
require_once('classes/hintRepository.php');

/**
 * Service zum Anlegen und Abfragen von eingegebenen Reports
 *
 * @author ju
 */
class HintService {

	private $hintRepository;

	function __construct() {
		$this->hintRepository = new HintRepository();
	}


	function saveNewHint($data) {

		$hintData = new HintData();
		$hintData->initialize();
		$this->assignValues($hintData, $data);

		$this->hintRepository->saveHint($hintData);

		return $hintData->getUniqueID();
	}

	private function assignValues($hint, $data) {
		$hint->lon = (float) $data['lon'];
		$hint->lat = (float) $data['lat'];
		$hint->hintWhen = $this->buildDateTime($data['hintWhen']);
		$hint->hintWhat = $data['hintWhat'];
	}


	private function buildDateTime($dateString)  {
		$dateArray = explode(' ', $dateString);
		// get the date and time separately
		$date = $dateArray[0];
		$time = $dateArray[1];

		// get days and reorder
		$singleDates = explode('.', $date);

		return $singleDates[2] . '-' . $singleDates[1] . '-' . $singleDates[0] . 'T' . $time . ':00Z';
	}



}

?>
