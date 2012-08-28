<?php
	require_once "config/config.php";
	class BaseDao {
		private $pdo;
		function __construct() {			 
			$this->pdo = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		}
	
		function prepareStatement($string) {
			return $this->pdo->prepare($string);
		}
		
		
		function beginTransaction() {
			$this->pdo->beginTransaction();
		}
		
		function lastInsertId() {
			return $this->pdo->lastInsertId();
		}
		
		
		function commit() {
			$this->pdo->commit();
		}
		
		function rollback() {
			$this->pdo->rollBack();
		}
	}


?>