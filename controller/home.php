<?php
require_once('views/home.view.php');
require_once('classes/recentlyStolen.php');
require_once('classes/bikeImages.php');
require_once('classes/hints.php');

class HomeController {

	public function execute() {
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

		$view = new HomeView($stolen, $hintList);
		return $view;

	}


}

?>
