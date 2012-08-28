<?php
require_once('classes/recentlyStolen.php');
require_once('classes/bikeImages.php');
require_once('views/reportList.view.php');
require_once('views/error.view.php');


class ReportListController {

	public function execute() {

		$recently = new RecentlyStolen(false); // no no limit *ohrwurm*
		$stolen = $recently->getRecentlyStolenReports();

		$view = new ReportListView($stolen);
		return $view;
	}

}


?>

