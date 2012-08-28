<?php
require_once('views/base.view.php');
require_once('classes/utils.php');

class ReportDetailsView extends BaseView {

	private $report;

	function __construct($singleReportData) {
		$this->report = $singleReportData;
	}

  protected function doShow() {
		?>

		<div class="fluid-row">
			<h2><?php echo $this->report['bikeType']; ?>, <?php echo $this->report['city']; ?> (Owner: <?php echo $this->report['creator']; ?>)</h2>
			<h5>Report-ID: <?php echo $_GET['reportID']; ?></h5>
			<hr />

			<div class="row-fluid">
				<div class="span6">

					<h3>Where did it happen?</h3>
					<table class="table table-bordered table-striped table-report-details">
						<tr>
							<td>Street / Housenumber</td>
							<td>
								<?php if (isset($this->report['road'])) echo $this->report['road'] . ' ' ; ?>
								<?php if (isset($this->report['house_number'])) echo $this->report['house_number']; ?>
						</tr>
						<tr>
							<td>Postcode / City</td>
							<td>
								<?php if (isset($this->report['postcode'])) echo $this->report['postcode'] . ' ' ; ?>
								<?php if (isset($this->report['city'])) echo $this->report['city']; ?>
							</td>
						</tr>
						<tr>
							<td>Longitude / Latitude</td>
							<td>
								<?php if (isset($this->report['lon'])) echo $this->report['lon'] . ' / ' ; ?>
								<?php if (isset($this->report['lat'])) echo $this->report['lat']; ?>
							</td>
						</tr>
						<tr>
							<td>The bike was last seen on</td>
							<td>
								<?php if (isset($this->report['lastSeen'])) echo readableDateTime($this->report['lastSeen']); ?>
							</td>
						</tr>
						<tr>
							<td>The owner noticed the theft on</td>
							<td>
								<?php if (isset($this->report['noticedTheft'])) echo readableDateTime($this->report['noticedTheft']); ?>
							</td>
						</tr>
						<tr>
							<td>How it happened</td>
							<td>
								<?php if (isset($this->report['circumstances'])) echo $this->report['circumstances']; ?>
							</td>
						</tr>
					</table>

					<h3>About this bike</h3>
					<table class="table table-bordered table-striped table-report-details">
						<tr>
							<td>Biketype</td>
							<td>
								<?php if (isset($this->report['bikeType'])) echo $this->report['bikeType']; ?>
						</tr>
						<tr>
							<td>Color</td>
							<td>
								<?php if (isset($this->report['color'])) echo $this->report['color']; ?>
							</td>
						</tr>
						<tr>
							<td>Description of the bike</td>
							<td>
								<?php if (isset($this->report['comment'])) echo $this->report['comment']; ?>
							</td>
						</tr>
						<tr>
							<td>Manufacturer</td>
							<td>
								<?php if (isset($this->report['manufacturer'])) echo $this->report['manufacturer']; ?>
							</td>
						</tr>
						<tr>
							<td>Price</td>
							<td>
								<?php if (isset($this->report['price'])) echo $this->report['price'] . ' €'; ?>
							</td>
						</tr>
						<tr>
							<td>Wheelsize</td>
							<td>
								<?php if (isset($this->report['wheelSize'])) echo $this->report['wheelSize'] . ' inch'; ?>
							</td>
						</tr>
						<tr>
							<td>Framenumber</td>
							<td>
								<?php if (isset($this->report['frameNumber'])) echo $this->report['frameNumber']; ?>
							</td>
						</tr>
						<tr>
							<td>Police registered number</td>
							<td>
								<?php if (isset($this->report['registrationCode'])) echo $this->report['registrationCode']; ?>
							</td>
						</tr>
						<tr>
							<td>Police Station</td>
							<td>
								<?php if (isset($this->report['policeStation'])) echo $this->report['policeStation']; ?>
							</td>
						</tr>
					</table>

					<?php if (isset($this->report['bikeparts']) && !empty($this->report['bikeparts'])) { ?>
					<h3>Special bike parts</h3>
					<table class="table table-bordered table-striped table-report-details">
						<?php foreach ($this->report['bikeparts'] as $bikepart) { ?>
						<tr><td><?php echo $bikepart['type']; ?></td><td><?php echo $bikepart['name']; ?></td></tr>
						<?php } ?>
					</table>

					<?php } ?>

				</div>
				<div class="span6">

					<h3>Images</h3>
					<div class="report-details-images clearfix">
						<?php
							if (isset($this->report['depiction'])) {
							foreach ($this->report['depiction'] as $img) { ?>
						<a href="<?php echo $img ?>" class="lightbox" rel="bikeimages[bikeimages]">
							<img src="/3rdparty/timthumb/timthumb.php?src=<?php echo $img; ?>&w=150" alt="" />
						</a>
						<?php } } ?>
					</div>

					<h3>Map</h3>
					<div class="bikemap bikemap-report-details" data-bikemaptype="report-details" data-bikemaplon="<?php echo $this->report['lon']; ?>" data-bikemaplat="<?php echo $this->report['lat']; ?>"></div>

					<p>
						Be a superhero, fight crimes!
						<?php if (isset($this->report['findersFee'])) { ?>
						<strong>There even is a finders fee: <?php echo $this->report['findersFee']; ?></strong>.
						<?php } ?>
					</p>

					<button class="btn btn-large btn-primary">I have seen something</button>

				</div>


			</div>


		</div>

		<?php
	}
}

?>

