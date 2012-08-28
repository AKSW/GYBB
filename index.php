<?php
require_once('classes/autoloader.php');
require_once('classes/user.php');


class FrontController {

	// Singleton Front Controller
	private static $instance = null;

	private $actionMapping = array(
		'home' => 'controller/home.php',
		'report'=> 'controller/report.php',
		'map'=> 'controller/map.php',
		'login'=> 'controller/login.php',
		'facebook' => 'controller/facebook.php',
		'logout' => 'controller/logout.php',
		'marker' => 'controller/marker.php',
		'reportDetails' => 'controller/reportDetails.php',
		'reportsInArea' => 'controller/reportsInArea.php',
		'suggestion' => 'controller/suggestion.php',
		'bikeImages' => 'controller/bikeImages.php',
		'addHint' => 'controller/addHint.php',
		'hints' => 'controller/hints.php',
		'statistics' => 'controller/statistics.php',
		'export' => 'controller/export.php',
		'deleteReport' => 'controller/deleteReport.php',
		'search' => 'controller/search.php',
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
