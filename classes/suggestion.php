<?php
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');

/**
 *
 **/
class Suggestion {

	private $type;
	private $term;
	private $allowedTypes = array('manufacturer', 'color', 'bikeType', 'policeStation', 'comptype', 'compname');


	function __construct($type, $term)  {
		$this->type = (string) $type;
		$this->term = (string) $term;
	}


	public function getSuggestionList()  {
		if (in_array($this->type, $this->allowedTypes))  {
			$query = $this->buildSparqlQuery();

			$curl = new CurlHelper();
			$suggestions = $curl->getSparqlResults($query);
			return $suggestions;
		} else  {
			return '';
		}
	}


	private function buildSparqlQuery()  {
		$sc = new SparqlConstants();
		$sparql = $sc->fullPrefixList;

		if ($this->type === 'comptype')  {
			$sparql .= '
				SELECT DISTINCT ?type WHERE {
					?parttype ' . $sc::RDFS . ':' . $sc::RDFS_LABEL . ' ?type . {
						SELECT * WHERE {
							?parttype ?p ' . $sc::GYBBO . ':' . $sc::BIKEFACTREL . ' .
						}
					}
					FILTER (regex(str(?type), "' . $this->term . '", "i")) .
				} ORDER BY ?type';

		} else if ($this->type === 'compname')  {
			// subsubsubsubsubselect yay!
			$sparql .= '
				SELECT DISTINCT ?name WHERE {
					?bikePartName ' . $sc::RDFS . ':' . $sc::RDFS_LABEL . ' ?name . {
						SELECT ?bikePartName WHERE {
							?what ?parttype ?bikePartName . {
								SELECT ?parttype WHERE {
									?parttype ?p ' . $sc::GYBBO . ':' . $sc::BIKEFACTREL . ' .
								}
							}
						}
					}
					FILTER (regex(str(?name), "' . $this->term . '", "i")) .
				} ORDER BY ?name';

		} else if ($this->type === 'bikeType') {
			// get all biketypes
			$sparql .= '
				SELECT DISTINCT ?type WHERE {
					?biketype ' . $sc::RDFS . ':' . $sc::RDFS_LABEL . ' ?type . {
						SELECT * WHERE {
							?biketype ?p ' . $sc::GYBBO . ':' . $sc::BIKE . ' .
						}
					}
					FILTER (regex(str(?type), "' . $this->term . '", "i")) .
				} ORDER BY ?type';

		} else  {
			$sparql .= '
				SELECT DISTINCT ?o WHERE { ?s ' . $sc::GYBBO . ':' . $this->type . ' ?o
					FILTER (regex(str(?o), "' . $this->term . '", "i")) .
				} ORDER BY ?o';
		}

		return $sparql;

	}


}

?>
