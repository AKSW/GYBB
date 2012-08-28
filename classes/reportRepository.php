<?php

require_once 'classes/queryFactory.php';
require_once 'classes/reportData.php';

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
        require_once 'classes/sparqlBuilder.php';
        $builder = new SparqlBuilder($this->insertPrefix(), $this->insertSuffix());
        $builder = $this->triplifyReportData($report, $builder);
        
        $this->execSparql($builder->toSparql());
      
    }
    
    
    private function triplifyReportData($reportData, $to) {
                                    
                    $to->subject(SparqlBuilder::GYBB, 'report' . $reportData->uniqueId());
                    
                    $predicates = array();
                    $predicates[] = predicateLiteral(SparqlBuilder::DC, "reportData", $reportData->user);
                    $predicates[] = predicateUri(SparqlBuilder::RDF, SparqlBuilder::RDF_TYPE,  SparqlBuilder::GYBBO, SparqlBuilder::REPORT );
                    $predicates[] = typedLiteral(SparqlBuilder::DC, "creationDate", $reportData->creationDate, SparqlBuilder::XSD_DATE);
                    
                    if (isset($reportData->dateoftheft) && is_a($reportData->dateoftheft, 'DateTime')) {
                        $predicates[] = typedLiteral(SparqlBuilder::DC, "creationDate", $reportData->dateoftheft, SparqlBuilder::XSD_DATE);
                    }   
                    
                    if (isset($this->placeoftheft) && is_a($this->placeoftheft, 'Point')) {
                        $predicates[] = typedLiteral(SparqlBuilder::GEO, "lat", $this->placeoftheft->lat, SparqlBuilder::XSD_DOUBLE);
                        $predicates[] = typedLiteral(SparqlBuilder::GEO, "lon", $this->placeoftheft->lon, SparqlBuilder::XSD_DOUBLE);
                        
                    }  
                    
                    $to->predicates($predicates);
                    
                    return $to;
                
    }

}

?>