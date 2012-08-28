<?php
require_once('3rdparty/fb/facebook.php');
require_once('config/config.php');
require_once('views/redirect.view.php');
require_once('classes/user.php');
require_once('views/home.view.php');
require_once('views/error.view.php');
require_once('classes/recentlyStolen.php');
require_once('classes/hints.php');

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
				// create a new user from facebook
				$user = new User($user_profile);
				User::putUserInSession($user);

				$recently = new RecentlyStolen(3);
				$stolen = $recently->getRecentlyStolenReports();

				foreach ($stolen as $key => $bike) {
					$bi = new BikeImages($bike['bikeID'], 1);
					$images = $bi->getImages();
					foreach ($images as $image) {
						$stolen[$key]['image'] = $image['image'];
					}
				}

				$hints = new Hints(false, 3);
				$hintList = $hints->getHints();

				return new HomeView($stolen, $hintList);

			}
		} catch (FacebookApiException $e) {

			error_log($e);
			$fbUser = null;

		}

		return new ErrorView();

	}

}

?>
