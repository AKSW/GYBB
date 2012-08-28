<?php
require_once('views/home.view.php');
require_once('classes/recentlyStolen.php');
require_once('classes/bikeImages.php');

class HomeController {


	public function execute() {
		$recently = new RecentlyStolen();
		$stolen = $recently->getRecentlyStolenReports();

		foreach ($stolen as $key => $bike) {
			$bi = new BikeImages($bike['bikeID'], 1);
			$images = $bi->getImages();
			foreach ($images as $image) {
				$stolen[$key]['image'] = $image['image'];
			}
		}

		$view = new HomeView($stolen);
		return $view;

	}


}

?>
