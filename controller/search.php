<?php
require_once('classes/autoloader.php');
require_once('config/config.php');
require_once('classes/sparqlConstants.php');
require_once('classes/curlHelper.php');
require_once('classes/validator.php');
require_once('views/search.view.php');

class SearchController {

	public function execute() {

		$sc = new SparqlConstants();
		$ch = new CurlHelper();
		$val = new Validator(false);
		$tempResults = false;
		$finalResults = array();
		$searchData = array();

		if (isset($_GET['search'])) {

			$searchValue = $val->cleanUp($_GET['search']);
			$searchTerms = explode(' ', $searchValue);

			foreach ($searchTerms as $search) {

				$query = $this->buildQuery($search);
				$results = $ch->getSparqlResults($query);

				// if we have results, build a minified result-array with
				// reportIDs (tmpArray) and save all the results-sets in tempResults
				$tmpArray = array();
				if (is_array($results) && !empty($results)) {
					foreach ($results as $key => $resultSet) {
						foreach ($resultSet as $singleResult) {
							$tmpArray[] = $singleResult->value;
						}
					}
				}
				$tempResults[] = $tmpArray;
			}

			// if we have any results from the query, intersect the tempResults-Arrays
			// to get the final result set
			if (!empty($tempResults)) {
				$count = count($tempResults);

				if ($count === 1) {
					$finalResults = $tempResults[0];
				} else {
					for ($i = 0; $i < $count; $i++) {
						if (empty($finalResults)) {
							$finalResults = @array_intersect($tempResults[$i], $tempResults[$i+1]);
						} else {
							$finalResults = array_intersect($finalResults, $tempResults[$i]);
						}
					}
				}
			}

			// finally fetch the report-data from the search-results and give it
			// to the search view
			$serachData = array();
			if (!empty($finalResults)) {
				foreach ($finalResults as $reportID) {
					$reportDetails = new ReportDetails(str_replace($sc->allPrefixes['gybb'], '', $reportID));
					$searchData[str_replace($sc->allPrefixes['gybb'], '', $reportID)] = $reportDetails->getReportDetails();
				}
			}
		}

    $searchData = $this->facetedSearchFilter($searchData);

		if ($this->isAjax()) {
			return new JsonView($searchData);
		} else {
			return new SearchView($searchData);
		}

	}


	// this is not optimal but the faster way:
	// filter the search-results with faceted attributes in pure
	// php and dont create new sparql-queries no one would understand :-)
	/*====================================================================*/
	private function facetedSearchFilter($data) {
		$allowedFields = array('state', 'city', 'type', 'color', 'manufacturer', 'wheelSize', 'findersFee');
		$searchFields = array();
		$filteredData = array();
		$searchVal = '';
		foreach ($allowedFields as $field) {
			$searchVal = 'search-' . $field;
			if (isset($_GET[$searchVal]) && !empty($_GET[$searchVal])) {
				$searchFields[] = $field;
			}
		}

		// now we have all search-fields and values -- let's
		// cycle through all results and filter them
		foreach ($data as $resultID => $result) {
			$allInThere = array();
			foreach ($searchFields as $field) {
				// it's a nice result if the field is there and equal to the checkboxs value
				if (isset($result[$field]) && $result[$field] === $_GET['search-' . $field]) {
					$allInThere[] = true;
				} else {
					$allInThere[] = false;
				}
			}

			// if every filter-var is true, we have a result
			if (!in_array(false, $allInThere)) {
				$filteredData[$resultID] = $result;
			}
		}

		return $filteredData;
	}


	private function buildQuery($search) {
		$sc = new SparqlConstants();
		$query = $sc->fullPrefixList;


		// this finds everything with webgefrickel, bike report etc.
		// TODO remove those fields from the search
		$query .= '
			SELECT DISTINCT ?report WHERE {

			?report rdf:type <http://getyourbikeback.webgefrickel.de/ontology/Report> .
			?report gybbo:describesTheftOf ?bike .
			OPTIONAL { ?hint gybbo:hintFor ?report . }
				{
					SELECT * WHERE {
						?report ?p ?searchReport .
							FILTER REGEX(?searchReport, "' . $search . '", "i") .
					}
				} UNION
				{
					SELECT * WHERE {
						?bike ?p ?searchBike .
							FILTER REGEX(?searchBike, "' . $search . '", "i") .
					}
				} UNION
				{
					SELECT * WHERE {
						?hint ?p ?searchHint .
							FILTER REGEX(?searchHint, "' . $search . '", "i") .
					}
				}
			} ';
		return $query;
	}


	private function isAjax() {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			return true;
		} else {
			return false;
		}
	}
}


?>
