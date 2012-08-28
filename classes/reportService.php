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

		// TODO sanitize postcode?!
		$report->postcode = $data['postcode'];

		$report->city = $data['city'];
		$report->lon = (float) $data['lon'];
		$report->lat = (float) $data['lat'];

		$theft = explode('.' , $data['dateOfTheft']);
		$report->dateOfTheft = $theft[2] . '-' . $theft[1] . '-' . $theft[0];

		// TODO hour xx:xx -- parse
		$report->lastSeen = $data['lastSeen'];
		$report->noticedTheft = $data['noticedTheft'];

		$report->registrationCode = $data['registrationCode'];
		$report->policeStation = $data['policeStation'];

		$report->bikeType = $data['bikeType'];
		$report->color = $data['color'];
		$report->comment = $data['comment'];
		$report->price = (int) $data['price'];
		$report->manufacturer = $data['manufacturer'];
		$report->wheelSize = (int) $data['wheelSize'];
		$report->frameNumber = $data['frameNumber'];

		// $this->components = $data['components'];
	}

}

?>
