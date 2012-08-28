<?php


/**
 * Service zum Anlegen und Abfragen von eingegebenen Reports
 *
 * @author ju
 */
class ReportService {
    
    private $reportRepository;
    
    private $bikePartService;
    
    
    
    function __construct() {
        require_once 'classes/reportRepository.php';
        $this->reportRepository = new ReportRepository();
        require_once 'classes/bikePartService.php';
        $this->bikePartService = new BikePartService();
        
        
    }
    
    
    function saveNewReport($data) {
        
        $reportData = new ReportData();
        $reportData->initialize();
        $this->assignValues($reportData, $data);
        
        $reportData->components = $this->bikePartService->mergeBikeParts($data);
		
		
		$this->reportRepository->saveReport($reportData);
		
		return $reportData->getUniqueID();

        
    }
    
    private function assignValues($report, $data) {
        
        $report->road = $data['road'];
		$report->housenumber = $data['housenumber'];

		// TODO sanitize postcode?!
		$report->postcode = $data['postcode'];

		$report->city = $data['city'];
		$report->lon = (float) $data['lon'];
		$report->lat = (float) $data['lat'];

		$theft = explode('.' , $data['dateoftheft']);
		$report->dateoftheft = $theft[2] . '-' . $theft[1] . '-' . $theft[0];

		// TODO hour xx:xx -- parse
		$report->timestart = $data['timestart'];
		$report->timeend = $data['timeend'];

		$report->codednumber = $data['codednumber'];
		$report->police = $data['police'];

		$report->biketype = $data['biketype'];
		$report->color = $data['color'];
		$report->description = $data['description'];
		$report->price = (int) $data['price'];
		$report->manufacturer = $data['manufacturer'];
		$report->size = (int) $data['size'];
		$report->framenumber = $data['framenumber'];

		// TODO this is wrong! -- only use saved images that are fine
		// $this->images = $data['images'];
		// $this->components = $data['components'];
    }
    
}

?>
