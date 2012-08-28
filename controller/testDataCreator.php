<?php
require_once('views/testDataCreator.view.php');
require_once('classes/reportData.php');
require_once('classes/reportRepository.php');
require_once('classes/reportService.php');
require_once('classes/hintData.php');
require_once('classes/hintRepository.php');
require_once('classes/hintService.php');
require_once('classes/user.php');
require_once('classes/validator.php');
require_once('classes/fileChecker.php');

class TestDataCreatorController {
	// numbers for the testdata, how many of each
	private $reports = 10;
	private $hintsMax = 3;
	private $compsMax = 5;

	public function execute() {

		for ($i = 0; $i < $this->reports; $i++) {
			$cleanData = $this->createReportData();
			$emptyfiles = array('bikeimages' => '');
			$filecheck = new FileChecker($emptyfiles);
			try {
				$bikeImages = $filecheck->checkFileErrors();
			} catch (Exception $e) {
				echo $e;
			}

			$service = new ReportService();
			$reportID = $service->saveNewReport($cleanData, $bikeImages);
			sleep(1); // because the reportIDs would be the same if we dont wait

			if ($reportID) { // we have successfully created a report, now for some hints
				$hintsTotal = mt_rand(0, $this->hintsMax);

				if ($hintsTotal > 0) {
					for ($j = 0; $j < $hintsTotal; $j++) {
						$hintData = $this->createHintData($reportID);
						$service = new HintService();
						$hintID = $service->saveNewHint($hintData);
						sleep(1);
					}
				}
			}

		}

		return new TestDataCreatorView();
	}

	private function createReportData() {
		// content for the test-data
		$streets = array("Hardenbergstr", "Kickerlingsberg", "Zum Acker", "Am Hof", "Humboldtstr", "Dorfstrasse");
		$zips = array("04194", "02674", "16593", "74783", "97534", "15374", "00329", "15264");
		$cities = array("Leipzig", "Berlin", "Prag", "Hintermberg", "Buxtehude", "Bielefeld", "Karlhausen", "Kalau");
		$circumstances = array("", "Went swimming, bike was gone", "Oh my god, someone stole my bike", "Went on a party, next day my bike was gone", "Dunno Stupid thief", "Fuck");
		$biketypes = array("Mountainbike", "Hollandrad", "Cruiser", "Trekkingbike", "Fixie", "Roadbike", "Citybike", "Downhillbike", "Trialbike");
		$colors = array("green", "blue", "red", "pink", "cyan", "magenta", "black", "beige", "grey", "silver", "silver-striped with teal", "orange");
		$comments = array("", "A nice bike it was", "Had some stickers on it", "was damn old and rusty", "Had no brakes anymore");
		$manufacturers = array("", "Cannondale", "selfmade", "Bianchi", "Radon", "Cube", "Kettler", "Diamant", "Storck");
		$wheelsizes = array("", "18", "20", "24", "26", "28", "29");
		$findersfees = array("", "nothing", "A bottle beer", "50 bucks", "one free hug", "A sixpack of beer", "Love");
		$comptypes = array("Saddle", "Fork", "Rims", "Switching Gear", "Handlebars");
		$compnames = array("Nicepart", "Awesomepart", "Wowthing", "Yeahstuff", "OMG-Part");

		$priceRange = array(50, 1800);
		$houseNumberRange = array(1, 120);
		$latRange = array(49.8, 53.25);
		$lonRange = array(9.15, 14.9);

		// let's build up the data-array
		$array = array();
		$threeDays = 3 * 24 * 60 * 60;
		$sixDays = 6 * 24 * 60 * 60;

		$array['road'] = $streets[array_rand($streets)];
		$array['house_number'] = mt_rand($houseNumberRange[0], $houseNumberRange[1]);
		$array['postcode'] = $zips[array_rand($zips)];
		$array['city'] = $cities[array_rand($cities)];
		// to get a nice float value multiply/divide by 10000
		$array['lon'] = (float) mt_rand($lonRange[0] * 10000, $lonRange[1] * 10000) / 10000;
		$array['lat'] = (float) mt_rand($latRange[0] * 10000, $latRange[1] * 10000) / 10000;
		// create some random dates by subtracting fromt 3 to 6 days for lastSeen and
		// subtracting at least one hour till up to 3 days for noticedTheft
		$array['lastSeen'] = date('d.m.Y H:i', (time() - mt_rand($threeDays, $sixDays)));
		$array['noticedTheft'] = date('d.m.Y H:i', (time() - mt_rand(60 * 60, $threeDays - 60 * 60)));
		$array['circumstances'] = $circumstances[array_rand($circumstances)];
		$array['bikeType'] = $biketypes[array_rand($biketypes)];
		$array['color'] = $colors[array_rand($colors)];
		$array['comment'] = $comments[array_rand($comments)];
		$array['price'] = mt_rand($priceRange[0], $priceRange[1]);
		$array['manufacturer'] = $manufacturers[array_rand($manufacturers)];
		$array['wheelSize'] = $wheelsizes[array_rand($wheelsizes)];
		$array['frameNumber'] = md5(time());
		$array['registrationCode'] = md5(time() / 2);
		$array['policeStation'] = $cities[array_rand($cities)];
		$array['findersFee'] = $findersfees[array_rand($findersfees)];

		// now for the components
		$numberOfComponents = mt_rand(0, $this->compsMax);
		if ($numberOfComponents > 0) {
			for ($i = 0; $i < $numberOfComponents; $i++) {
				$array['comptype'][$i] = $comptypes[array_rand($comptypes)];
				$array['compname'][$i] = $compnames[array_rand($compnames)] . ' ' . mt_rand(1, 9);
			}
		} else {
			$array['comptype'] = array();
			$array['compname'] = array();
		}

		return $array;
	}


	private function createHintData($reportID) {
		$array = array();
		$latRange = array(49.8, 53.25);
		$lonRange = array(9.15, 14.9);
		$hintwhats = array(
			'I think I may have seen your bike', 'I am the thief', 'I have your bike',
			'I have seen a cat that looks just like your bike', 'Hey wassup'
		);

		$array['reportID'] = $reportID;
		$array['hintWhen'] = date('d.m.Y H:i', (time() - mt_rand(0, 24 * 60 * 60)));
		$array['hintWhat'] = $hintwhats[array_rand($hintwhats)];
		$array['lon'] = (float) mt_rand($lonRange[0] * 10000, $lonRange[1] * 10000) / 10000;
		$array['lat'] = (float) mt_rand($latRange[0] * 10000, $latRange[1] * 10000) / 10000;
		return $array;

	}

}

?>

