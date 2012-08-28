<?php
require_once('classes/queryFactory.php');
require_once('classes/reportData.php');
require_once('classes/sparqlBuilder.php');

class ReportRepository extends QueryFactory {

	function __construct() {
		parent::__construct();
	}

	/**
	 * Konstruiert einen Sparql query, der dem Geo intersect entspricht.
	 * @param type $report
	 */
	public function queryByArea($upperLeft, $lowerRight) {
		/*
		 * Bsp sparql:
		 * PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
		  select  ?subject, ?o where  {?subject  geo:lon ?o. FILTER(?o > 12.0)}
		 */
		// im einfachsten Fall in SQL ist es ein:
		// between(min_x, max_x) and between(min_y, max_y).
		// TODO: kurz nachdenken was passiert wenn wir einen Vorzeichen wechsel
		//haben .. sollte aber passen
	}

	public function saveReport($report, $bikeImages) {
		//TODO triplify/merge place
		//TODO truplify/merge policeStation
		//Triplify Bike
		$sparqlBuilder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
		$builder = $this->triplifyBike($report, $bikeImages, $sparqlBuilder);
		$this->execSparql($builder->toSparql());

		//Triplify Report
		$sparqlBuilder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
		$builder = $this->triplifyReportData($report, $sparqlBuilder);
		$this->execSparql($builder->toSparql());
	}

	private function triplifyBike($bikeData, $bikeImages, $to) {
		$to->subject(SparqlBuilder::GYBB, $bikeData->getUniqueBikeID());

		$predicates = array();
		//triplify bikeparts
		if (isset($bikeData->components) && is_array($bikeData->components)) {
			foreach ($bikeData->components as $bikePart) {
				$bikePartType = $bikePart->type;
				if (!empty($bikePartType)) $predicates[] = predicateUri(SparqlBuilder::GYBBO, $bikePartType->predicate, SparqlBuilder::GYBB, $bikePart->uri);
			}
		}
		if (!empty($bikeData->bikeType)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::BIKETYPE, $bikeData->bikeType);
		if (!empty($bikeData->color)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::COLOR, $bikeData->color);
		if (!empty($bikeData->comment)) $predicates[] = predicateLiteral(SparqlBuilder::RDFS, SparqlBuilder::COMMENT, $bikeData->comment);
		if (!empty($bikeData->manufacturer)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::MANUFACTURER, $bikeData->manufacturer);
		if (!empty($bikeData->frameNumber)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::FRAMENUMBER, $bikeData->frameNumber);
		if (!empty($bikeData->registrationCode)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::REGISTRYCODE, $bikeData->registrationCode);
		if (!empty($bikeData->policeStation)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::REPORTEDTO, $bikeData->policeStation);
		if (!empty($bikeData->price)) $predicates[] = typedLiteral(SparqlBuilder::GYBBO, SparqlBuilder::PRICE, $bikeData->price, SparqlBuilder::XSD_INT);
		if (!empty($bikeData->wheelSize)) $predicates[] = typedLiteral(SparqlBuilder::GYBBO, SparqlBuilder::WHEELSIZE, $bikeData->wheelSize, SparqlBuilder::XSD_INT);

    // TODO this is just a test thats adds the same image to every bike
		if (is_array($bikeImages) && !empty($bikeImages)) {
			foreach ($bikeImages as $image) {
				if (substr($image, -4) === '.jpg') {
					$predicates[] = predicateLiteral(SparqlBuilder::FOAF, SparqlBuilder::DEPICTION, BASE_URL . 'uploads/' . $image);
				}
			}
		}

		$to->predicates($predicates);

		return $to;
	}

	private function triplifyReportData($reportData, $to) {

		$to->subject(SparqlBuilder::GYBB, $reportData->getUniqueID());

		$predicates = array();
		$predicates[] = predicateUri(SparqlBuilder::RDF, SparqlBuilder::RDF_TYPE, SparqlBuilder::GYBBO, SparqlBuilder::REPORT);
		$predicates[] = typedLiteral(SparqlBuilder::DCT, SparqlBuilder::CREATED, $reportData->created, SparqlBuilder::XSD_DATE);

		// TODO dc:creator user (email? name?)

		// DATE AND TIME
		if (!empty($reportData->dateOfTheft) && preg_match('/\d{4}\-\d{2}\-\d{2}/', $reportData->dateOfTheft)) {
			$predicates[] = typedLiteral(SparqlBuilder::GYBBO, SparqlBuilder::DATEOFTHEFT, $reportData->dateOfTheft, SparqlBuilder::XSD_DATE);
		}
		if (!empty($reportData->lastSeen) && preg_match('/\d{2}:\d{2}/', $reportData->lastSeen)) {
			$predicates[] = typedLiteral(SparqlBuilder::GYBBO, SparqlBuilder::LASTSEEN, $reportData->lastSeen, SparqlBuilder::XSD_TIME);
		}
		if (!empty($reportData->noticedTheft) && preg_match('/\d{2}:\d{2}/', $reportData->noticedTheft)) {
			$predicates[] = typedLiteral(SparqlBuilder::GYBBO, SparqlBuilder::NOTICEDTHEFT, $reportData->noticedTheft, SparqlBuilder::XSD_TIME);
		}

    // user email and name
    $predicates[] = typedLiteral(SparqlBuilder::DC, SparqlBuilder::CREATOR, User::getCurrentUser()->name, SparqlBuilder::XSD_STRING);
    $predicates[] = typedLiteral(SparqlBuilder::FOAF, SparqlBuilder::MBOX, User::getCurrentUser()->email, SparqlBuilder::XSD_STRING);

		// address data
		if (!empty($reportData->lon) && !empty($reportData->lat)) {
			$predicates[] = typedLiteral(SparqlBuilder::GEO, SparqlBuilder::LAT, $reportData->lat, SparqlBuilder::XSD_DOUBLE);
			$predicates[] = typedLiteral(SparqlBuilder::GEO, SparqlBuilder::LON, $reportData->lon, SparqlBuilder::XSD_DOUBLE);
		}

		// road, city, postcode, housenumber
		if (!empty($reportData->road)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::ROAD, $reportData->road);
		if (!empty($reportData->house_number)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::HOUSENUMBER, $reportData->house_number);
		if (!empty($reportData->city)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::CITY, $reportData->city);
		if (!empty($reportData->postcode)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::POSTCODE, $reportData->postcode);

		//relation zu Bike IRI:
		$predicates[] = predicateUri(SparqlBuilder::GYBBO, SparqlBuilder::DESCRIBESTHEFTOF, SparqlBuilder::GYBB, $reportData->getUniqueBikeID());

		$to->predicates($predicates);

		return $to;
	}

}

?>
