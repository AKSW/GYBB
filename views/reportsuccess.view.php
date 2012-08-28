<?php
require_once('views/base.view.php');

class ReportSuccessView extends BaseView {

	private $reportID;

	function __construct($id) {
		$this->reportID = $id;
	}

	protected function doShow() { ?>

	<div class="fluid-row">

		<h2>Yay! Your Report has been saved.</h2>
		<p>
			<a href="index.php?action=home">Return to the Homepage</a> |
			<a href="index.php?action=reportDetails&amp;reportID=<?php echo $this->reportID; ?>">Show your report</a>
		</p>

	</div>

	<?php
	}
}

?>
