<?php
require_once('classes/autoloader.php');
require_once('classes/user.php');
require_once('controller/userError.php');

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
		'reportDetails' => 'controller/reportDetails.php',
		'reportsInArea' => 'controller/reportsInArea.php',
		'suggestion' => 'controller/suggestion.php',
		'bikeImages' => 'controller/bikeImages.php',
		'addHint' => 'controller/addHint.php',
		'me' => 'controller/me.php',
		'hints' => 'controller/hints.php',
		'help' => 'controller/help.php',
		'export' => 'controller/export.php',
		'deleteReport' => 'controller/deleteReport.php',
		'updateReport' => 'controller/updateReport.php',
		'search' => 'controller/search.php',
		'reportList' => 'controller/reportList.php',
		'hintList' => 'controller/hintList.php',
		//cron controllers
		'statistics' => 'controller/statistics.php',
		'void'=> 'controller/void.php',
		// test data creation
		'testDataCreator' => 'controller/testDataCreator.php',
	);

	// if a no logged in user tries to do an unsafe action specified
	// here, he/she will be forwarded to an error-view
	private $unsafeActions = array(
		'me', 'export', 'deleteReport', 'report', 'statistics', 'void', 'testDataCreator'
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
		$action = 'home'; //default
		if (array_key_exists('action', $_REQUEST)) {
			$action = $_REQUEST['action'];
		}

		$actionMapping = $this->actionMapping;

		if (array_key_exists ($action, $actionMapping)) {
			// the action exists -- now check if the current user is
			// allowed to execute the action
			$loggedIn = (User::getCurrentUser()) ? true : false;

			if (!$loggedIn && in_array($action, $this->unsafeActions)) {
				echo $loggedIn;
				$userError = new UserErrorController();
				$result = $userError->execute();
				$result->show();
			} else {
				require_once($this->actionMapping[$action]);
				$className = strtoupper(substr($action, 0, 1)) . substr($action, 1) . 'Controller';
				$controller = new $className();
				$result = $controller->execute();
				$result->show();
			}

		} else {
			print "Error action undefined " . $action;
			$this->reportError();
		}

	}


	private function reportError() {
	}

}

$main = FrontController::getInstance();
$main->execute();

?>
