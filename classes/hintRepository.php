<?php
require_once('classes/queryFactory.php');
require_once('classes/hintData.php');
require_once('classes/sparqlBuilder.php');

class HintRepository extends QueryFactory {

	function __construct() {
		parent::__construct();
	}

	public function saveHint($hint) {
		// Triplify Report
		$sparqlBuilder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
		$builder = $this->triplifyHintData($hint, $sparqlBuilder);
		$this->execSparql($builder->toSparql());
	}


	private function triplifyHintData($hint, $to) {

		$to->subject(SparqlBuilder::GYBB, $hint->getUniqueID());

		$predicates = array();
		$predicates[] = predicateUri(SparqlBuilder::RDF, SparqlBuilder::RDF_TYPE, SparqlBuilder::GYBBO, SparqlBuilder::HINT);
		$predicates[] = typedLiteral(SparqlBuilder::DCT, SparqlBuilder::CREATED, $hint->created, SparqlBuilder::XSD_DATETIME);

		if (!empty($hint->hintWhen) && preg_match('/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}Z/', $hint->hintWhen)) {
			$predicates[] = typedLiteral(SparqlBuilder::GYBBO, SparqlBuilder::HINTWHEN, $hint->hintWhen, SparqlBuilder::XSD_DATETIME);
		}

    // user email and name
		if (User::getCurrentUser()) {
			$predicates[] = typedLiteral(SparqlBuilder::DC, SparqlBuilder::CREATOR, User::getCurrentUser()->name, SparqlBuilder::XSD_STRING);
			$predicates[] = typedLiteral(SparqlBuilder::FOAF, SparqlBuilder::MBOX, User::getCurrentUser()->email, SparqlBuilder::XSD_STRING);
		} else {
			$predicates[] = typedLiteral(SparqlBuilder::DC, SparqlBuilder::CREATOR, 'anonymous', SparqlBuilder::XSD_STRING);
		}

		// address data
		if (!empty($hint->lon) && !empty($hint->lat)) {
			$predicates[] = typedLiteral(SparqlBuilder::GEO, SparqlBuilder::LAT, $hint->lat, SparqlBuilder::XSD_DOUBLE);
			$predicates[] = typedLiteral(SparqlBuilder::GEO, SparqlBuilder::LON, $hint->lon, SparqlBuilder::XSD_DOUBLE);
		}

		// hintWhat
		if (!empty($hint->hintWhat)) $predicates[] = predicateLiteral(SparqlBuilder::GYBBO, SparqlBuilder::HINTWHAT, $hint->hintWhat);

		//relation zu report:
		$predicates[] = predicateUri(SparqlBuilder::GYBBO, SparqlBuilder::HINTFOR, SparqlBuilder::GYBB, $hint->getUniqueReportID());

		$to->predicates($predicates);

		return $to;
	}

}

?>
