<?php
require_once('views/base.view.php');
require_once('classes/utils.php');
require_once('classes/user.php');

class ReportDetailsView extends BaseView {

	private $report;
	private $hints;
	private $reportID;

	function __construct($singleReportData, $hintsData) {
		$this->report = $singleReportData;
		$this->hints = $hintsData;

		if (isset($_GET['reportID'])) {
			$this->reportID = $_GET['reportID'];
		} else if (isset($_POST['reportID'])) {
			$this->reportID = $_POST['reportID'];
		} else {
      $this->reportID = 0;
		}
	}

  protected function doShow() {
		?>

		<div id="report-details-view">
			<ul class="nav nav-tabs" id="nav-report">
				<li class="">
					<a href="#reportdetails"><span class="badge">1</span> Report Details</a>
				</li>
				<li class="active">
					<a href="#hints"><span class="badge">2</span> Hints</a>
				</li>
				<?php
					$curUser = User::getCurrentUser();
					if ($curUser && $this->report['mbox'] === $curUser->email &&
							$this->report['creator'] === $curUser->name) { ?>
				<li class="active">
					<a href="#admin"><span class="badge">3</span> Admin Area</a>
				</li>
				<?php } ?>
			</ul>

			<div class="tab-content">
				<div class="tab-pane" id="reportdetails">
					<h2><?php echo $this->report['bikeType']; ?>, <?php echo $this->report['city']; ?> (Owner: <?php echo $this->report['creator']; ?>)</h2>
					<h5>Report-ID: <?php echo $this->reportID; ?></h5>
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

							<?php if (isset($this->report['depiction']) && !empty($this->report['depiction'])) { ?>
							<h3>Images</h3>
							<div class="report-details-images clearfix">
								<?php foreach ($this->report['depiction'] as $img) { ?>
								<a href="<?php echo $img ?>" class="lightbox" rel="bikeimages[bikeimages]">
									<img src="/3rdparty/timthumb/timthumb.php?src=<?php echo $img; ?>&w=150" alt="" />
								</a>
								<?php } ?>
							</div>
							<?php } ?>

							<h3>Map</h3>
							<div class="map-wrapper">
								<div class="bikemap bikemap-report-details" data-bikemaptype="report-details" data-bikemaplon="<?php echo $this->report['lon']; ?>" data-bikemaplat="<?php echo $this->report['lat']; ?>"></div>
							</div>

							<p>
								Be a superhero, fight crimes!
								<?php if (isset($this->report['findersFee'])) { ?>
								<strong>There even is a finders fee: <?php echo $this->report['findersFee']; ?></strong>.
								<?php } ?>
							</p>

							<button class="btn btn-large btn-primary btn-hint">I have seen something</button>

							<div id="hint-form">
								<h3>Awesome!</h3>
								<p>
									First, click the map to specify the location, where you have seen something.
									Then fill out the simple form.
								</p>
								<div class="row-fluid">
									<div class="span6">
										<div class="bikemap bikemap-add-hint" data-bikemaptype="add-hint" data-bikemaplon="<?php echo $this->report['lon']; ?>" data-bikemaplat="<?php echo $this->report['lat']; ?>"></div>
									</div>

									<div class="span6">
										<form action="index.php" class="well" method="post" accept-charset="utf-8">
											<input type="hidden" name="action" value="addHint" />
											<input type="hidden" name="reportID" value="<?php echo $this->reportID; ?>" />
											<?php
												inputHelper('hintWhen', '', '', true);
												inputHelper('hintWhat', '', '', true, 'textarea');
												inputHelper('lon', '', '', true);
												inputHelper('lat', '', '', true);
											?>
											<br />
											<input type="submit" name="submit" value="Submit hint"  class="btn btn-large btn-primary" />

										</form>
									</div>
								</div>
							</div>
						</div>
					</div> <!-- end reportdetails -->
				</div>

				<div class="tab-pane active" id="hints">
					<h2><?php echo $this->report['bikeType']; ?>, <?php echo $this->report['city']; ?> (Owner: <?php echo $this->report['creator']; ?>)</h2>
					<h5>Report-ID: <?php echo $this->reportID; ?></h5>
					<hr />
					<div class="row-fluid">
						<div class="span4">
							<?php if (!empty($this->hints)) : ?>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Hint</th>
										<th>Time of observation</th>
										<th>Hint given on</th>
										<th>given by</th>
									</tr>
								</thead>
								<tbody>

									<?php foreach ($this->hints as $hint) { ?>
									<tr>
										<td><?php echo $hint['hintWhat']; ?></td>
										<td><?php echo readableDateTime($hint['hintWhen']); ?></td>
										<td><?php echo readableDateTime($hint['created']); ?></td>
										<td><?php echo $hint['hintUser']; ?></td>
									</tr>
									<?php } ?>

								</tbody>
							</table>
							<p>
								You have seen something, too? Give a hint!
							</p>
							<button class="btn btn-large btn-primary btn-hint">I have seen something</button>
							<?php else : ?>
							<p>
								There are no hints yet. Give a hint!
							</p>
							<button class="btn btn-large btn-primary btn-hint">I have seen something</button>
							<?php endif; ?>
						</div>
						<div class="span8">
							<div class="bikemap bikemap-hints" data-bikemaptype="hints"
								data-bikemaplon="<?php echo $this->report['lon']; ?>"
								data-bikemaplat="<?php echo $this->report['lat']; ?>"
								data-bikemapreportid="<?php echo $this->reportID; ?>">
							</div>

						</div>
					</div>
				</div>

				<?php
					$curUser = User::getCurrentUser();
					if ($curUser && $this->report['mbox'] === $curUser->email &&
							$this->report['creator'] === $curUser->name) { ?>
				<div class="tab-pane" id="admin">
					<form action="/index.php" method="post" accept-charset="utf-8">
						<input type="hidden" name="action" value="deleteReport" />
						<input type="hidden" name="reportID" value="<?php echo $this->reportID; ?>" />

						<h1>DANGER ZONE!</h1>
						<p>
							Click the button below to delete your report and all associated data and hints.
						</p>

						<p><input class="btn btn-large btn-danger" type="submit" value="Delete my report" /></p>
					</form>
				</div>
				<?php } ?>

			</div>

		</div>

		<?php
	}
}

?>

