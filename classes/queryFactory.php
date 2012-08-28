<?php
require_once('config/config.php');
require_once('classes/sparqlBuilder.php');
require_once('classes/sparqlConstants.php');

class QueryFactory extends SparqlConstants {
	/* Default Graph URI */

	private $connection;

	function __construct() {
		parent::__construct();

		if (!function_exists('odbc_connect')) throw new RepositoryException("odbc extension not found");

		$this->connection = odbc_connect(VOS_DSN, VOS_USER, VOS_PASSWORD);
		if (false == $this->connection) {
			throw new RepositoryException($this->getLastError());
		}
		@odbc_autocommit($this->connection, true);
	}

	function __destruct() {
		$this->_closeConnection();
	}

	function asUniqueIdentifier($iri, $sequence) {
		return '`sql:sprintf_iri("' .  $iri . '#%s" , sql:sequence_next("' . $sequence . '"))`';
	}

	/**
		* Closes a current connection if it exists
		*/
	private function _closeConnection() {
		if (is_resource($this->connection)) {
			// close connection
			@odbc_close($this->connection);
		}
	}

	public function execSparql($sparqlQuery) {
		$sparqlQuery = addcslashes($sparqlQuery, '\'\\');

		// build Virtuoso/PL query
		$virtuosoPl = 'SPARQL ' . $sparqlQuery;
		$resultId = odbc_exec($this->connection, $virtuosoPl);

		if (false === $resultId) {
			$message = sprintf('SPARQL Error: %s in query: %s', $this->getLastError(), htmlentities($sparqlQuery));
			throw new RepositoryException($message);
		}

		return $resultId;
	}

	public function ttl($ttl, $graph = ONTOLOGY_GRAPH) {
		$ttl = addcslashes($ttl, '\'\\');
		$virtuosoPl = 'CALL DB.DBA.TTLP( \'' . $ttl . '\',\'\',\'' . $graph . '\',0)';
		$resultId = odbc_exec($this->connection, $virtuosoPl);

		if (false === $resultId) {
			$message = sprintf('SPARQL Error: %s in query: %s', $this->getLastError(), htmlentities($sparqlQuery));
			throw new RepositoryException($message);
		}

		return $resultId;

	}

	/**
	* Returns the last ODBC error message and number.
	*
	* @return string
	*/
	public function getLastError() {
		if (null !== $this->connection) {
			$message = sprintf('%s (%i)', odbc_errormsg($this->connection), odbc_error($this->connection));
			return $message;
		}
	}


	public function fetchResult($resultId) {
		if ($resultId) {
			return odbc_fetch_array($resultId);
		} else {
			return false;
		}
	}


	public function fetchAll($resultId) {
		if ($resultId) {
			return odbc_fetch_array($resultId);
		} else {
			return false;
		}
	}

}



class RepositoryException extends Exception {

	function __construct($message) {
		parent::__construct($message);
	}

}

?>
