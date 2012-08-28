<?php
require_once('classes/bikePartService.php');
require_once('classes/reportRepository.php');

/**
 * Service zum Anlegen und Abfragen von eingegebenen Reports
 *
 * @author ju
 */
class ReportService {

	private $reportRepository;
	private $bikePartService;

	function __construct() {
		$this->reportRepository = new ReportRepository();
		$this->bikePartService = new BikePartService();
	}


	function saveNewReport($data, $bikeImages) {

		$reportData = new ReportData();
		$reportData->initialize();
		$this->assignValues($reportData, $data);

		$reportData->components = $this->bikePartService->mergeBikeParts($data);

		$this->reportRepository->saveReport($reportData, $bikeImages);

		return $reportData->getUniqueID();

	}

	private function assignValues($report, $data) {

		$report->road = $data['road'];
		$report->house_number = $data['house_number'];

		$report->postcode = $data['postcode'];

		$report->city = $data['city'];
		$report->lon = (float) $data['lon'];
		$report->lat = (float) $data['lat'];

		$report->lastSeen = $this->buildDateTime($data['lastSeen']);
		$report->noticedTheft = $this->buildDateTime($data['noticedTheft']);
		$report->circumstances = $data['circumstances'];

		$report->registrationCode = $data['registrationCode'];
		$report->policeStation = $data['policeStation'];
		$report->findersFee = $data['findersFee'];

		$report->bikeType = $data['bikeType'];
		$report->color = $data['color'];
		$report->comment = $data['comment'];
		$report->price = (int) $data['price'];
		$report->manufacturer = $data['manufacturer'];
		$report->wheelSize = (int) $data['wheelSize'];
		$report->frameNumber = $data['frameNumber'];
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
