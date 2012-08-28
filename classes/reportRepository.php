<?php
require_once('classes/queryFactory.php');
require_once('classes/reportData.php');
require_once('classes/sparqlBuilder.php');

class ReportRepository extends QueryFactory {

	function __construct() {
		parent::__construct();
	}


	public function saveReport($report, $bikeImages) {
		$sparqlBuilder = new SparqlBuilder();
		$builder = $this->triplifyBike($report, $bikeImages, $sparqlBuilder);
		$this->execSparql($builder->toSparql());

		$sparqlBuilder = new SparqlBuilder();
		$builder = $this->triplifyReportData($report, $sparqlBuilder);
		$this->execSparql($builder->toSparql());

		$sparqlBuilder = new SparqlBuilder(ONTOLOGY_GRAPH);
		$builder = $this->triplifyBikeType($report, $sparqlBuilder);
		$this->execSparql($builder->toSparql());
	}


	private function triplifyBikeType($bikeData, $to) {
		// Hollandrad rdf:type owl:Class .
		// Hollandrad rdfs:subClassOf gybbo:Bike .
		// Hollandrad
		$to->subject(SparqlConstants::GYBBO, urlencode(ucfirst($bikeData->bikeType)));

		$classPredicates  = array();
		$classPredicates[] = predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, SparqlConstants::OWL, SparqlConstants::OWL_CLASS);
		$classPredicates[] = predicateUri(SparqlConstants::RDFS, SparqlConstants::RDFS_SUBCLASS, SparqlConstants::GYBBO, SparqlConstants::BIKE);
		$classPredicates[] = predicateLiteral(SparqlConstants::RDFS, SparqlConstants::RDFS_LABEL, $bikeData->bikeType);

		$to->predicates($classPredicates);
		return $to;
	}


	private function triplifyBike($bikeData, $bikeImages, $to) {
		$to->subject(SparqlConstants::GYBB, $bikeData->getUniqueBikeID());

		$predicates = array();
		// triplify bikeparts
		if (isset($bikeData->components) && is_array($bikeData->components)) {
			foreach ($bikeData->components as $bikePart) {
				$bikePartType = $bikePart->type;
				if (!empty($bikePartType)) $predicates[] = predicateUri(SparqlConstants::GYBBO, $bikePartType->predicate, SparqlConstants::GYBB, $bikePart->uri);
			}
		}

		if (!empty($bikeData->bikeType)) $predicates[] = predicateLiteral(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, urlencode(ucfirst($bikeData->bikeType)));
		if (!empty($bikeData->color)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::COLOR, $bikeData->color);
		if (!empty($bikeData->comment)) $predicates[] = predicateLiteral(SparqlConstants::RDFS, SparqlConstants::COMMENT, $bikeData->comment);
		if (!empty($bikeData->manufacturer)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::MANUFACTURER, $bikeData->manufacturer);
		if (!empty($bikeData->frameNumber)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::FRAMENUMBER, $bikeData->frameNumber);
		if (!empty($bikeData->registrationCode)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::REGISTRATIONCODE, $bikeData->registrationCode);
		if (!empty($bikeData->policeStation)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::POLICESTATION, $bikeData->policeStation);
		if (!empty($bikeData->price)) $predicates[] = typedLiteral(SparqlConstants::GYBBO, SparqlConstants::PRICE, $bikeData->price, SparqlConstants::XSD_INT);
		if (!empty($bikeData->wheelSize)) $predicates[] = typedLiteral(SparqlConstants::GYBBO, SparqlConstants::WHEELSIZE, $bikeData->wheelSize, SparqlConstants::XSD_INT);

		if (is_array($bikeImages) && !empty($bikeImages)) {
			foreach ($bikeImages as $image) {
				if (substr($image, -4) === '.jpg') {
					$predicates[] = predicateLiteral(SparqlConstants::FOAF, SparqlConstants::DEPICTION, BASE_URL . 'uploads/' . $image);
				}
			}
		}

		$to->predicates($predicates);

		return $to;
	}

	private function triplifyReportData($reportData, $to) {

		$to->subject(SparqlConstants::GYBB, $reportData->getUniqueID());

		$predicates = array();
		$predicates[] = predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, SparqlConstants::GYBBO, SparqlConstants::REPORT);
		$predicates[] = typedLiteral(SparqlConstants::DCT, SparqlConstants::CREATED, $reportData->created, SparqlConstants::XSD_DATETIME);

		if (!empty($reportData->lastSeen) && preg_match('/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}Z/', $reportData->lastSeen)) {
			$predicates[] = typedLiteral(SparqlConstants::GYBBO, SparqlConstants::LASTSEEN, $reportData->lastSeen, SparqlConstants::XSD_DATETIME);
		}
		if (!empty($reportData->noticedTheft) && preg_match('/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}Z/', $reportData->noticedTheft)) {
			$predicates[] = typedLiteral(SparqlConstants::GYBBO, SparqlConstants::NOTICEDTHEFT, $reportData->noticedTheft, SparqlConstants::XSD_DATETIME);
		}

    // user email and name
    $predicates[] = typedLiteral(SparqlConstants::DC, SparqlConstants::CREATOR, User::getCurrentUser()->name, SparqlConstants::XSD_STRING);
    $predicates[] = typedLiteral(SparqlConstants::FOAF, SparqlConstants::MBOX, User::getCurrentUser()->email, SparqlConstants::XSD_STRING);

		// address data
		if (!empty($reportData->lon) && !empty($reportData->lat)) {
			$predicates[] = typedLiteral(SparqlConstants::GEO, SparqlConstants::LAT, $reportData->lat, SparqlConstants::XSD_DOUBLE);
			$predicates[] = typedLiteral(SparqlConstants::GEO, SparqlConstants::LON, $reportData->lon, SparqlConstants::XSD_DOUBLE);
		}

		// road, city, postcode, housenumber
		if (!empty($reportData->road)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::ROAD, $reportData->road);
		if (!empty($reportData->house_number)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::HOUSENUMBER, $reportData->house_number);
		if (!empty($reportData->city)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::CITY, $reportData->city);
		if (!empty($reportData->postcode)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::POSTCODE, $reportData->postcode);
		if (!empty($reportData->circumstances)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::CIRCUMSTANCES, $reportData->circumstances);
		if (!empty($reportData->findersFee)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::FINDERSFEE, $reportData->findersFee);

		//state active or not active
		$predicates[] = typedLiteral(parent::GYBBO, parent::STATE, parent::STATE_OPEN, parent::XSD_STRING);


		//relation zu Bike IRI:
		$predicates[] = predicateUri(SparqlConstants::GYBBO, SparqlConstants::DESCRIBESTHEFTOF, SparqlConstants::GYBB, $reportData->getUniqueBikeID());

		$to->predicates($predicates);

		return $to;
	}

}

?>
