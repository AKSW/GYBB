<?php
require_once('classes/utils.php');
require_once('views/base.view.php');

class ReportView extends BaseView {

  protected function doShow() {

?>

<div class="row-fluid" id="view-report">
	<form method="post" action="index.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="report" />

		<ul class="nav nav-tabs" id="nav-report">
			<li class="active">
				<a href="#location"><span class="badge">1</span> Location</a>
			</li>
			<li>
				<a href="#date-and-time"><span class="badge">2</span> Date and time</a>
			</li>
			<li>
				<a href="#your-bike"><span class="badge">3</span> Your bike</a>
			</li>
			<li>
				<a href="#image-upload"><span class="badge">4</span> Image Upload</a>
			</li>
			<li>
				<a href="#police-information"><span class="badge">5</span> Police Information</a>
			</li>
			<li>
				<a href="#summary"><span class="badge">6</span> Summary</a>
			</li>
		</ul>

		<div class="tab-content">

			<div class="tab-pane active" id="location">
				<fieldset class="well">
					<legend>Place of theft</legend>
					<p>
						Set Marker per Click on the map or insert address and/or choose from suggestions below:
					</p>
					<div class="row-fluid">
						<ul class="span4">
						<?php
							inputHelper('road', 'li', 'The street where your bike was stolen');
							inputHelper('house_number', 'li', 'Housenumber nearby');
							inputHelper('postcode', 'li');
							inputHelper('city', 'li');
							inputHelper('lon', 'li', '', true);
							inputHelper('lat', 'li', '', true);
						?>
						</ul>

						<div class="span8">
							<div class="bikemap bikemap-report" data-bikemaptype="report"></div>
							<button class="btn btn-findme">Center on my location</button>
						</div>

					</div>
					<div class="row-fluid">
						<div id="suggestions">

						</div>
					</div>
				</fieldset>
			</div>

			<div class="tab-pane" id="date-and-time">
				<fieldset class="well">
					<legend>Date and time of theft</legend>
					<p>
						Input date in format: DD.MM.YYYY HH:MM or use the included widgets (just click it!)
					</p>
					<ul>
						<?php
							inputHelper('lastSeen', 'li', '', true);
							inputHelper('noticedTheft', 'li', '', true);
							inputHelper('circumstances', 'li', 'Describe the circumstances of the bike-theft', false, 'textarea');
						?>
					</ul>
				</fieldset>
			</div>

			<div class="tab-pane" id="your-bike">

				<fieldset class="well">
					<legend>Details of your bike</legend>
					<div class="row-fluid">
						<ul class="span4">
							<?php
								inputHelper('bikeType', 'li', 'Mountainbike, Racing-Bike etc.', true, 'text', 'suggestion');
								inputHelper('color', 'li', 'green, black, purple', true, 'text', 'suggestion');
								inputHelper('comment', 'li', '...', false, 'textarea');
								inputHelper('price', 'li', 'approx. when stolen');
								inputHelper('manufacturer', 'li', 'Cannondale, Bianchi...', false, 'text', 'suggestion');
								inputHelper('wheelSize', 'li', 'usually 26 or 28');
								inputHelper('frameNumber', 'li');
							?>
						</ul>

						<div class="span8">
							<h3>Additional bike parts</h3>
							<p>
								enter type of component in the first field and the name of the component in the second<br />
								for example Type: switch gear Name:XT
							</p>

							<ul class="form-inline">
								<li class="bikeparts" data-bikeparts-counter="1">
									<label for="comptype-1">Type</label><input type="text" name="comptype[]" id="comptype-1" class="suggestion" />
									<label for="compname-1">Name</label><input type="text" name="compname[]" id="compname-1" class="suggestion" />
									<button class="btn btn-add-more-parts"><i class="icon-plus"></i></button>
								</li>
							</ul>
						</div>
					</div>
				</fieldset>
			</div>

			<div class="tab-pane" id="image-upload">
				<fieldset class="well">
					<!-- TODO make a beautiful form -->
					<legend>Images of your bike</legend>
					<p>
						JPG-Images only, maxmimum file size: 1 MB. Click the plus-button to add more than one image.
					</p>
					<ul>
						<li class="bikeimage-uploadarea" data-upload-counter="1">
							<label for="bikeimage-1">Image No. <span class="counter">1</span></label>
							<input type="file" name="bikeimages[]" id="bikeimage-1" />
							<button class="btn btn-add-more-images"><i class="icon-plus"></i></button>
						</li>
						<li></li>
					</ul>
				</fieldset>
			</div>

			<div class="tab-pane" id="police-information">
				<fieldset class="well">
					<legend>Police related information</legend>
					<ul>
						<?php
							inputHelper('registrationCode', 'li');
							inputHelper('policeStation', 'li', '', false, 'text', 'suggestion');
							inputHelper('findersFee', 'li', 'Beer, money - to improve hints');
						?>
					</ul>
				</fieldset>
			</div>

			<div class="tab-pane" id="summary">
				<div class="well">
					<h2>Summary</h2>

					<div class="row-fluid">
						<table class="span4 table table-condensed">
							<?php
								summaryHelper('road');
								summaryHelper('house_number');
								summaryHelper('postcode');
								summaryHelper('city');
								summaryHelper('lon');
								summaryHelper('lat');
							?>
						</table>

						<table class="span4 table table-condensed">
							<?php
								summaryHelper('lastSeen');
								summaryHelper('noticedTheft');
								summaryHelper('circumstances');
								summaryHelper('registrationCode');
								summaryHelper('policeStation');
								summaryHelper('findersFee');
							?>
						</table>

						<table class="span4 table table-condensed">
							<?php
								summaryHelper('bikeType');
								summaryHelper('color');
								summaryHelper('comment');
								summaryHelper('price');
								summaryHelper('manufacturer');
								summaryHelper('wheelSize');
								summaryHelper('frameNumber');
							?>
						</table>
					</div>

					<div class="row-fluid">
						<div id="summary-images" class="span6">
							<h4>Your Images</h4>
							<div class="summary-images-container">
							</div>
						</div>

						<div id="summary-components" class="span6">
							<h4>Bike Components</h4>
							<div class="summary-components-container">
							</div>
						</div>

					</div>
				</div>

				<div class="row-fluid">
					<button class="btn btn-primary pull-right">Publish my report</button>
					<button class="btn btn-print pull-right">Print this summary</button>
					<!-- TODO <button class="btn">Publish and send to Facebook</button> -->
				</div>

			</div>

		</div>
	</form>
</div>

<?php

	}
}

?>
