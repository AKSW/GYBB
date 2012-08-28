	<?php
require_once('views/base.view.php');

class HintSuccessView extends BaseView {

	private $reportID;

	function __construct($id) {
		$this->reportID = $id;
	}

	protected function doShow() {
		?>
		<div class="fluid-row">

			<h2>Yay! Your Hint has been saved.</h2>
			<p>
				You, sir or madam, are a good person.
			</p>
			<p>
				<a href="index.php?action=home">Return to the Homepage</a> |
				<a href="index.php?action=reportDetails&amp;reportID=<?php echo $this->reportID; ?>">Back to the report</a>
			</p>

		</div>

		<?php
	}
}

?>
