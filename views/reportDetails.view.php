<?php
require_once('views/base.view.php');
require_once('classes/utils.php');

class ReportDetailsView extends BaseView {

	private $singleReportData;

	function __construct($singleReportData) {
		$this->singleReportData = $singleReportData;
	}

  protected function doShow() {
    echo '<pre>' . print_r($this->singleReportData, 1) . '</pre>';

		?>

		<div class="fluid-row">
			<h2>Your Report!</h2>

			<table class="table table-bordered table-striped">
				<?php
					foreach ($this->singleReportData as $field => $value) {
						reportDetailsHelper($field, $value);
					}
				?>
			</table>
			<div class="images">
				<?php
					if (isset($this->singleReportData['depiction'])) {
					foreach ($this->singleReportData['depiction'] as $img) { ?>
				<a href="<?php echo $img ?>" class="lightbox" rel="bikeimages[bikeimages]">
					<img src="/3rdparty/timthumb/timthumb.php?src=<?php echo $img; ?>&w=200" alt="" />
				</a>
				<?php } } ?>

			</div>
		</div>

		<?php
	}
}

?>

