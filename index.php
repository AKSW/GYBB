<?php

include_once 'classes/user.php';


 function __autoload($class) {
 // ReportData oder BaseDao
 // macht dann ein include gegen classes/reportData.php bzw classes/dao/baseDao.php
 
 	$filename = strtolower(substr($class,0,1)).substr($class, 1).'php';
 	if (is_file('classes/'.$filename)) {
 		include_once 'classes/'.$filename;
 	} else if (is_file('classes/dao/'.$filename)) {
 		include_once 'classes/dao/'.$filename;
 	}

 }
 
 
 
 


class FrontController {

	// Singleton Front Controller
	private static $instance = null;


	private $actionMapping = array(
		'home' => 'controller/home.php',
		'report'=> 'controller/report.php',
		'map'=> 'controller/map.php',
		'login'=> 'controller/login.php',
		'facebook' => 'controller/facebook.php',
		"logout" => "controller/logout.php",
		'marker' => 'controller/marker.php'
);

	
		
	// Zugriff auf Singleton instanz.	
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new FrontController();			
		}
		return self::$instance;
	}


	
	public function execute() {
		session_start();
		$action = 'home';//default
		if (array_key_exists('action', $_REQUEST)) {
			$action = $_REQUEST['action'];
			
		}
		$actionMapping = $this->actionMapping;
		if (array_key_exists ($action, $actionMapping)) {
			require_once($this->actionMapping[$action]);
			$className = strtoupper(substr($action, 0, 1)) . substr($action, 1) . 'Controller';
			$controller = new $className();
			$result = $controller->execute();
			$result->show();
				
		} else {
			print "Error action undefined " . $action;
			$this->reportError();
		} 	
				

	}


	private function reportError() {
	}



}



ini_set("display_errors", "stdout");		


$main  = FrontController::getInstance();
$main->execute(); 

?>
