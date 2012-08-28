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

	public function saveReport($report) {
              //TODO triplify/merge place
              //TODO truplify/merge policeStation
          
              //Triplify Bike
                $sparqlBuilder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
		$builder = $this->triplifyBike($report, $sparqlBuilder);
		$this->execSparql($builder->toSparql());
            
                //Triplify Report
                $sparqlBuilder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
		$builder = $this->triplifyReportData($report, $sparqlBuilder);
		$this->execSparql($builder->toSparql());
                
                
	}

        private function triplifyBike($bikeData, $to) {
          
		$to->subject(SparqlBuilder::GYBB, $bikeData->getUniqueBikeID());

                $predicates = array();		
                //triplify bikeparts
		if (isset($bikeData->components) && is_array($bikeData->components)) {
			foreach($bikeData->components as $bikePart) {
				$bikePartType = $bikePart->type;
				$predicates[] = predicateUri(parent::GYBBO, $bikePartType->predicate, parent::GYBB, $bikePart->uri);
			}
		}
                $predicates[] = predicateLiteral(parent::GYBBO, parent::BIKETYPE, $bikeData->biketype);
                $predicates[] = predicateLiteral(parent::GYBBO, parent::COLOR, $bikeData->color);
                $predicates[] = predicateLiteral(parent::GYBBO, parent::DESCRIPTION, $bikeData->description);
                $predicates[] = predicateLiteral(parent::GYBBO, parent::PRICE, $bikeData->price);
                $predicates[] = predicateLiteral(parent::GYBBO, parent::MANUFACTURER, $bikeData->manufacturer);
                $predicates[] = predicateLiteral(parent::GYBBO, parent::WHEELSIZE, $bikeData->size);
                $predicates[] = predicateLiteral(parent::GYBBO, parent::FRAMENUMBER, $bikeData->framenumber);
                $predicates[] = predicateLiteral(parent::GYBBO, parent::REGISTRYCODE, $bikeData->codednumber);
                $predicates[] = predicateLiteral(parent::GYBBO, parent::REPORTEDTO, $bikeData->police);
                //TODO bike not part of bikeData?
                $predicates[] = predicateLiteral(parent::GYBBO, parent::DESCRIBESTHEFTOF, $bikeData->bike);

                $to->predicates($predicates);

		return $to;
          
        }

	private function triplifyReportData($reportData, $to) {
                
		
          
		$to->subject(SparqlBuilder::GYBB, $reportData->getUniqueID());

		$predicates = array();		
                $predicates[] = predicateUri(SparqlBuilder::RDF, SparqlBuilder::RDF_TYPE,  SparqlBuilder::GYBBO, SparqlBuilder::REPORT);
                $predicates[] = typedLiteral(SparqlBuilder::DC, "creationDate", $reportData->creationDate, SparqlBuilder::XSD_DATE);
                
		
		if (isset($reportData->dateoftheft) && is_a($reportData->dateoftheft, 'DateTime')) {
			$predicates[] = typedLiteral(SparqlBuilder::GYBBO, "dateOfTheft", $reportData->dateoftheft, SparqlBuilder::XSD_DATE);
		}

		if (isset($reportData->lon) && isset($reportData->lat)) {
			$predicates[] = typedLiteral(SparqlBuilder::GEO, "lat", $reportData->lat, SparqlBuilder::XSD_DOUBLE);
			$predicates[] = typedLiteral(SparqlBuilder::GEO, "lon", $reportData->lon, SparqlBuilder::XSD_DOUBLE);
		}

		//relation zu Bike IRI:	
		$predicates[] = predicateUri(parent::GYBBO, "describesTheftOf", parent::GYBB, $reportData->getUniqueBikeID());
		
		$to->predicates($predicates);

		return $to;

	}

}

?>
