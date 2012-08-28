<?php
require_once('config/config.php');

/**
 * A small helper class for curl
 **/
class CurlHelper {


	public function getSparqlResults($sparqlQuery) {
		$response = '';
		$url = BASE_ENDPOINT_URL . ':' . BASE_ENDPOINT_PORT . '/sparql?default-graph-uri=' . urlencode(DEFAULT_LGD_GRAPH);
		$format = '&format=' . urlencode('application/sparql-results+json');
		$params = '&timeout=0';

		$requestString =  $url . '&query=' . urlencode($sparqlQuery) . $format . $params;

		// get the query results with curl, accept json only
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $requestString);
		curl_setopt($ch, CURLOPT_ENCODING, 'application/json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);

		$allResults = json_decode($response);
		return $allResults->results->bindings;
	}


	public function getExportData($sparqlQuery, $shortFormat) {
    $response = '';
		$format = '';

		$url = BASE_ENDPOINT_URL . ':' . BASE_ENDPOINT_PORT . '/sparql?default-graph-uri=' . urlencode(DEFAULT_LGD_GRAPH);
		$params = '&timeout=0';

		switch ($shortFormat) {
			case 'rdf':
				$format = '&format=' . urlencode('application/rdf+xml');
				break;
			case 'json':
				$format = '&format=' . urlencode('application/rdf+json');
				break;
			case 'ttl':
				$format = '&format=' . urlencode('text/rdf+n3');
				break;
			case 'txt':
				$format = '&format=' . urlencode('text/plain');
				break;
			default:
				$format = '';
				break;
		}


		$requestString =  $url . '&query=' . urlencode($sparqlQuery) . $format . $params;

		// get the query results with curl, accept json only
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $requestString);
		// curl_setopt($ch, CURLOPT_ENCODING, 'application/json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

}



?>
