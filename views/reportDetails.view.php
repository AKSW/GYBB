<?php
require_once('views/base.view.php');

class ReportDetailsView extends BaseView {

	private $singleReportData;

	function __construct($singleReportData) {
		$this->singleReportData = $singleReportData;
	}

	protected function doShow() {

		// TODO generate a global labels array -- use the values from there everywhere
		$demLabels = array(
      'creationDate' => 'Time of Creation'
		);

		?>

		<div class="fluid-row">

			<h2>Your Report!</h2>

			<table>
			<?php foreach ($this->singleReportData as $field => $value) : ?>
				<tr>
					<td><?php echo /* $demLabels[$field]; */ $field; ?></td>
					<td><?php echo $value; ?></td>
				</tr>

			<?php endforeach; ?>
			</table>

		</div>

		<?php
	}
}

?>

