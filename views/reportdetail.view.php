<?php
require_once('views/base.view.php');

class ReportDetailView extends BaseView {

	private $reportID;

	function __construct($id) {
		$this->reportID = $id;
	}

	protected function doShow() { ?>

		<div class="fluid-row">

			<h2>Your Report!</h2>
			<p>
			Lot's of details, bike type, map etc. ID: <?php echo $this->reportID; ?>
			</p>

		</div>

		<?php
	}
}

?>

