<?php
require_once('classes/queryFactory.php');
require_once('classes/hintData.php');
require_once('classes/sparqlBuilder.php');

class HintRepository extends QueryFactory {

	function __construct() {
		parent::__construct();
	}

	public function saveHint($hint) {
		$sparqlBuilder = new SparqlBuilder();
		$builder = $this->triplifyHintData($hint, $sparqlBuilder);
		$this->execSparql($builder->toSparql());
	}


	private function triplifyHintData($hint, $to) {

		$to->subject(SparqlConstants::GYBB, $hint->getUniqueID());

		$predicates = array();
		$predicates[] = predicateUri(SparqlConstants::RDF, SparqlConstants::RDF_TYPE, SparqlConstants::GYBBO, SparqlConstants::HINT);
		$predicates[] = typedLiteral(SparqlConstants::DCT, SparqlConstants::CREATED, $hint->created, SparqlConstants::XSD_DATETIME);

		if (!empty($hint->hintWhen) && preg_match('/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}Z/', $hint->hintWhen)) {
			$predicates[] = typedLiteral(SparqlConstants::GYBBO, SparqlConstants::HINTWHEN, $hint->hintWhen, SparqlConstants::XSD_DATETIME);
		}

    // user email and name
		if (User::getCurrentUser()) {
			$predicates[] = typedLiteral(SparqlConstants::DC, SparqlConstants::CREATOR, User::getCurrentUser()->name, SparqlConstants::XSD_STRING);
			$predicates[] = typedLiteral(SparqlConstants::FOAF, SparqlConstants::MBOX, User::getCurrentUser()->email, SparqlConstants::XSD_STRING);
		} else {
			$predicates[] = typedLiteral(SparqlConstants::DC, SparqlConstants::CREATOR, 'anonymous', SparqlConstants::XSD_STRING);
		}

		// address data
		if (!empty($hint->lon) && !empty($hint->lat)) {
			$predicates[] = typedLiteral(SparqlConstants::GEO, SparqlConstants::LAT, $hint->lat, SparqlConstants::XSD_DOUBLE);
			$predicates[] = typedLiteral(SparqlConstants::GEO, SparqlConstants::LON, $hint->lon, SparqlConstants::XSD_DOUBLE);
		}

		// hintWhat
		if (!empty($hint->hintWhat)) $predicates[] = predicateLiteral(SparqlConstants::GYBBO, SparqlConstants::HINTWHAT, $hint->hintWhat);

		//relation zu report:
		$predicates[] = predicateUri(SparqlConstants::GYBBO, SparqlConstants::HINTFOR, SparqlConstants::GYBB, $hint->getUniqueReportID());

		$to->predicates($predicates);

		return $to;
	}

}

?>
