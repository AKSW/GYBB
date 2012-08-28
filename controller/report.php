<?php
require_once('views/report.view.php');
require_once('views/home.view.php');
require_once('classes/reportData.php');
require_once('classes/point.php');
require_once('classes/reportRepository.php');
require_once('classes/user.php');
require_once('classes/dao/reportDataDao.php');
require_once('classes/bikePartService.php');

class ReportController {

	public function execute() {

		if ($_SERVER['REQUEST_METHOD'] == "GET") {
			return $this->showNewReport();
		} else if ($_SERVER['REQUEST_METHOD'] == "POST") {


			//Datenobjekt erstellen
			$reportData = new ReportData();
			$reportData->user = 'foo';
			$reportData->biketype = $_POST['biketype'];
			$reportData->color= $_POST['color'];
			$theft = explode('.' , $_POST['dateoftheft']);

			// TODO sanitize user input first
			$reportData->dateoftheft =	$theft[2] . '-' . $theft[1] . '-' . $theft[0] ;


			$placeOfTheft = new Point(floatval($_POST['lon']), floatval($_POST['lat']));
			$reportData->placeoftheft = $placeOfTheft;

			$now = getdate();

			$reportData->creationDate = $now['year'] . '-' . $now['mon'] . '-' . $now['mday'];

			$reportData->description = $_POST['description'];

			$bikePartService = new BikePartService();

			$reportData->components = $bikePartService->createBikeParts($_POST);

			$reportData->price = intval($_POST['price']);
			$reportData->manufacturer = $_POST['manufacturer'];
			$reportData->size = intval($_POST['size']);
			$reportData->codednumber = $_POST['codednumber'];
			$reportData->police = $_POST['police'];
			$reportData->framenumber = $_POST['framenumber'];


			$repository = new ReportRepository();
			$repository->saveReport($reportData);

			// TODO
			//in DB speichern.
			// restliche daten übernehmen


			return new HomeView();
		}

		return new HomeView();

	}

	private function showNewReport(){
		return $view = new ReportView();
	}


}

?>
