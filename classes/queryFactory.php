<?php

require_once 'config/config.php';
require_once 'classes/sparqlBuilder.php';
class QueryFactory extends SparqlConstants {
    /* Default Graph URI */

      const DEFAULT_LGD_GRAPH = "http://getyourbikeback.webgefrickel.de/reports.rdf";

    private $connection;
    private $graphURI;

    function __construct($graphURI = QueryFactory::DEFAULT_LGD_GRAPH) {
        if (!function_exists('odbc_connect'))
            throw new RepositoryException("odbc extension not found");


        $this->connection = odbc_connect(VOS_DSN, VOS_USER, VOS_PASSWORD);
        if (false == $this->connection) {
            throw new RepositoryException($this->getLastError());
        }
        @odbc_autocommit($this->connection, true);

        $this->graphURI = $graphURI;
    }

    function graphURI() {
      return $this->graphURI;
    }

    function __destruct() {
        $this->_closeConnection();
    }

    function insertPrefix() {
      return "INSERT INTO <" . $this->graphURI() . "> {\n";
    }

    function insertSuffix() {
      return "  } \n";
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

//    public function addStatement($subject, $predicate, $object) {
//        if(!strpos($subject, 'iri(')) {
//            $subject = '<' . $subject . '>' ;
//        }
//
//        $insertSparql = 'INSERT INTO GRAPH <' . $this->graphURI . '> {
//                ' . $subject . ' <' . $predicate . '> ' . $object . '
//            }';
//
//        //@odbc_result_all($this->_execSparql($insertSparql));
//
//    }
//
//    public function ttl($ttl) {
//
//
//        $resultId = odbc_exec($this->connection, 'CALL DB.DBA.TTLP('
//                . "'" . $ttl  . "',"
//                . "'', "
//                . "'" . $this->graphURI . "',"
//                . "0" .  ')');
//
//
//        if (false === $resultId) {
//            print_r(str_replace("\n", "<br>", htmlentities($ttl)) );
//            $message = sprintf('SPARQL Error: %s in query: %s', $this->getLastError(), htmlentities($sparqlQuery));
//            throw new RepositoryException($message);
//        }
//
//        //odbc_result_all($resultId);
//
//        return $resultId;
//    }

    public function execSparql($sparqlQuery) {
        $sparqlQuery = addcslashes($sparqlQuery, '\'\\');



        // build Virtuoso/PL query
        $virtuosoPl = 'SPARQL ' . $sparqlQuery;
        //$virtuosoPl = 'CALL DB.DBA.SPARQL_EVAL(\'' . $sparqlQuery . '\', ' . $graphUri . ', 0);';
//        print_r("<p>Query</p>");
//        print_r(htmlentities($virtuosoPl));
//        print_r("<p>Query</p>");
//
        $resultId = @odbc_exec($this->connection, $virtuosoPl);

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

}

class RepositoryException extends Exception {

    function __construct($message) {
        parent::__construct($message);
    }

}

?>
