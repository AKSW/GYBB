<?php
require_once('3rdparty/fb/facebook.php');
require_once('config/config.php');
require_once('views/redirect.view.php');
require_once('classes/user.php');
require_once('classes/dao/userDao.php');
require_once('views/home.view.php');
require_once('views/error.view.php');

// Facebook Login controller
class FacebookController {

	private $fb = null;

	function __construct() {
		$this->fb = new Facebook(array('appId' => APP_ID, 'secret' => APP_SECRET));
		Facebook::$CURL_OPTS[CURLOPT_CONNECTTIMEOUT] = 30;
	}


	public function execute() {

		$fbUser = null;

		try {

			$fbUser = $this->fb->getUser();
			if (!$fbUser) {
				return new RedirectView($this->fb->getLoginUrl(array('scope' => 'email')));
			}

			$user_profile = $this->fb->api('/me');
			if (!empty($user_profile)) {
				// User erstellen und in der Datenbank speichern:
				// 1. User Objekt erstellen
				// 2. Datenbank eintrag holen (falls existiert)
				$userDao = UserDao::getInstance();
				$user = $userDao->checkUser($user_profile['id'], 'facebook');

				$user->oauthId = $user_profile['id'];
				$user->oauthProvider = 'facebook';
				$user->email = $user_profile['email'];
				$user->name = $user_profile['name'];

				// 3. Datenbank eintrag aktualisiern
				$user = $userDao->save($user);
				// 4. Session neu initialisieren
				User::putUserInSession($user);

				return new HomeView();

			}
		} catch (FacebookApiException $e) {

			error_log($e);
			$fbUser = null;

		}

		return new ErrorView();

	}

}

?>
