<?php
require_once('views/base.view.php');

class ReportDeletedView extends BaseView {

	protected function doShow() {
		?>

		<div class="fluid-row">

			<h2>The report has been deleted.</h2>
			<p>
				<a href="index.php?action=home">Return to the Homepage</a>
			</p>

		</div>

		<?php
	}
}

?>
