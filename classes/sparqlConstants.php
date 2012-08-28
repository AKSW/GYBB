<?php
require_once('config/config.php');

class SparqlConstants {

  public $allPrefixes = array(
		'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
		'contact' => 'http://www.w3.org/2000/10/swap/pim/contact#',
		'dbo' => 'http://dbpedia.org/ontology/',
		'owl' => 'http://www.w3.org/2002/07/owl#',
		'xsd' => 'http://www.w3.org/2001/XMLSchema#',
		'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
		'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
		'foaf' => 'http://xmlns.com/foaf/0.1/',
		'dc' => 'http://purl.org/dc/elements/1.1/',
		'dct' => 'http://purl.org/dc/terms/',
		'dbp' => 'http://dbpedia.org/resource/',
		'dbpedia2' => 'http://dbpedia.org/property/',
		'dbpedia' => 'http://dbpedia.org/',
		'skos' => 'http://www.w3.org/2004/02/skos/core#',
		'gybb' => 'http://demo.aksw.org/resource/',
		'gybbo' => 'http://demo.aksw.org/ontology/',
		'gybbstat' => 'http://demo.aksw.org/datacube/' ,
		'geo' => 'http://www.w3.org/2003/01/geo/wgs84_pos#',
		'virtrdf' => 'http://www.openlinksw.com/schemas/virtrdf#',
		'void' => 'http://rdfs.org/ns/void#',
		'qb' => 'http://purl.org/linked-data/cube#'
	);


	const GYBB = "gybb";
	const GYBBSTAT = "gybbstat";
	const DC = "dc";
	const DCT = "dct";
	const CREATOR = "creator";
	const FOAF = 'foaf';
	const MBOX = 'mbox';
	const GEO = "geo";
	const QB = "qb";
	const XSD = "xsd";
	const XSD_DATE = "xsd:date";
	const XSD_DATETIME = "xsd:dateTime";
	const XSD_DOUBLE = "xsd:double";
	const XSD_STRING = "xsd:string";
	const XSD_INT = "xsd:integer";

	const GYBBO = "gybbo";

	const STATE	= "state";

	const STATE_OPEN = "open";
	const STATE_FOUND = "found";
	const STATE_CLOSED = "closed";

	const REPORT = "Report";
	const BIKE = "Bike";
	const BIKEFACT = "BikeFact";
	const BIKEFACTREL = "BikeFactRelation";
	const COLOR = "color";
	const WHEELSIZE = "wheelSize";
	const MANUFACTURER = "manufacturer";
	const COMMENT = "comment";
	const LABEL = "label";
	const CIRCUMSTANCES = "circumstances";
	const FRAMENUMBER = "frameNumber";
	const PRICE = "price";
	const REGISTRATIONCODE = "registrationCode";
	const POLICESTATION = "policeStation";
	const FINDERSFEE = "findersFee";
	const DESCRIBESTHEFTOF = "describesTheftOf";
	const DEPICTION = "depiction";

	const HINTWHAT = "hintWhat";
	const HINTWHEN = "hintWhen";
	const HINT = "Hint";
	const HINTFOR = "hintFor";

	const CREATED = "created";
	const LASTSEEN = "lastSeen";
	const NOTICEDTHEFT = "noticedTheft";

	const ROAD = "road";
	const HOUSENUMBER = "house_number";
	const POSTCODE = "postcode";
	const CITY = "city";
	const LAT = "lat";
	const LON = "lon";

	const RDF = "rdf";
	const RDF_TYPE = "type";

	const OWL = "owl";
	const OWL_CLASS = "Class";
	const RDFS_PROPERTY = "Property";
	const RDFS = "rdfs";
	const RDFS_LABEL = "label";
	const RDFS_SUBCLASS = "subClassOf";
	const RDFS_SUBPROP = "subPropertyOf";

	public $fullPrefixList = '';


	function __construct() {
		if (empty($this->fullPrefixList)) {
			foreach ($this->allPrefixes as $short => $urls) {
				$this->fullPrefixList .= 'PREFIX ' . $short . ': <' . $urls . "> \n";
			}
			$this->fullPrefixList . "\n";
		}
	}

	/**
	 * Hilfsmethode für URIs -- set hardcoded to true if your want the literal uri
	 * and not something like gybbo:nanana
	 */
	function ttl_uri($prefix, $value, $hardcoded = true) {
		if ($hardcoded)  {
			return (string) '<' . $this->allPrefixes[$prefix] . $value . '>';
		} else {
			return (string) $prefix . ':' . (string) $value . ' ';
		}
	}

	function ttl_literal($value) {
		return '"' . addcslashes((string) $value, "\n\r\t\"") . '"';
	}

}


?>
