<?php
require_once "views/base.view.php";

	class ReportView extends BaseView {


		protected function doShow() {

//TODO: get post parameter benoetigt?
?>

<div class="row-fluid" id="view-report">
	<form method="post" action="index.php?action=report">
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
					<legend>Angaben zum Ort</legend>
					<p>
						Textdummy -- click on map
					</p>
					<div class="row-fluid">
						<ul class="span4">
							<li>
								<label for="road">Straße</label>
								<input type="text" id="road" name="road" />
							</li>
							<li>
								<label for="housenumber">Hausnummer</label>
								<input type="text" id="housenumber" name="housenumber" />
							</li>
							<li>
								<label for="postcode">Postleitzahl</label>
								<input type="text" id="postcode"	name="postcode" />
							</li>
							<li>
								<label for="city">Ort</label>
								<input type="text" id="city" name="city" />
							</li>
							<li>
								<label for="lon">Longitude</label>
								<input type="text" id="lon" name="lon" required="required" />
							</li>
							<li>
								<label for="lat">Latitude</label>
								<input type="text" id="lat" name="lat" required="required" />
							</li>
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
				<fieldset>
					<legend>Angaben zum Tatzeitraum</legend>
					<ul>
						<li>
							<label for="dateoftheft">Date of Theft</label>
							<input type="text" id="dateoftheft" name="dateoftheft" required="required" />
						</li>
						<li>
							<label for="timestart">Time from</label>
							<input type="text" name="timestart" value="" id="timestart" />
						</li>
						<li>
							<label for="timeend">Time to</label>
							<input type="text" name="timeend" value="" id="timeend" />
						</li>
					</ul>
				</fieldset>
			</div>

			<div class="tab-pane" id="your-bike">

				<fieldset class="well">
					<legend>Angaben zum Fahrrad</legend>
					<div class="row-fluid">
						<ul class="span4">
							<li>
								<label for="biketype">Fahrradart</label>
								<input type="text" id="biketype" name="biketype" required="required" />
							</li>
							<li>
								<label for="color">Farbe</label>
								<input type="text" id="color" name="color" required="required" />
							</li>
							<li>
								<label for="description">Beschreibung</label>
								<textarea rows="5" col="50" id="description" name="description"> </textarea>
							</li>
							<li>
								<label for="price">Kaufpreis</label>
								<input type="text" id="price" name="price"/>
							</li>
							<li>
								<label for="manufacturer">Hersteller</label>
								<input type="text" id="manufacturer" name="manufacturer"/>
							</li>
							<li>
								<label for="size">Laufradgroesse in Zoll</label>
								<input type="text" id="size" name="size"/>
							</li>
							<li>
								<label for="framenumber">Rahmennummer</label>
								<input type="text" id="framenumber" name="framenumber"/>
							</li>
						</ul>

						<div class="span8">
							<h3>Additional bike parts</h3>
							<p>
								Text Komponenten
							</p>

							<ul class="form-inline">
								<li class="bikeparts" data-bikeparts-counter="1">
									<label for="comptype-1">Type</label><input type="text" name="comptype[]" id="comptype-1" />
									<label for="compname-1">Name</label><input type="text" name="compname[]" id="compname-1" />
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
				<fieldset>
					<legend>Polizeiliche Informationen</legend>
					<ul>
						<li>
						<label for="codednumber">Nummer Fahrradcodierung</label>
						<input type="text" id="codednumber" name="codednumber"/>
					</li>
					<li>
						<label for="police">zuständiges Polizeirevier</label>
						<input type="text" id="police" name="police"/>
					</li>
					</ul>
				</fieldset>
			</div>

			<div class="tab-pane" id="summary">
				<h2>Summary</h2>

				<div class="row-fluid">
					<table class="span4 table table-condensed">
						<tr>
							<td>Street</td>
							<td id="summary-road"></td>
						</tr>
						<tr>
							<td>House number</td>
							<td id="summary-housenumber"></td>
						</tr>
						<tr>
							<td>Postal Code</td>
							<td id="summary-postcode"></td>
						</tr>
						<tr>
							<td>City</td>
							<td id="summary-city"></td>
						</tr>
						<tr>
							<td>Longitude</td>
							<td id="summary-lon"></td>
						</tr>
						<tr>
							<td>Latitude</td>
							<td id="summary-lat"></td>
						</tr>
					</table>

					<table class="span4 table table-condensed">
						<tr>
							<td>Date of theft</td>
							<td id="summary-dateoftheft"></td>
						</tr>
						<tr>
							<td>Time from</td>
							<td id="summary-timestart"></td>
						</tr>
						<tr>
							<td>Time to</td>
							<td id="summary-timeend"></td>
						</tr>
						<tr>
						  <td>Coded Number</td>
						  <td id="summary-codednumber"></td>
						</tr>
						<tr>
						  <td>Police Station</td>
						  <td id="summary-police"></td>
						</tr>
					</table>

					<table class="span4 table table-condensed">
						<tr>
							<td>Your biketype</td>
							<td id="summary-biketype"></td>
						</tr>
						<tr>
							<td>Color</td>
							<td id="summary-color"></td>
						</tr>
						<tr>
							<td>Description</td>
							<td id="summary-description"></td>
						</tr>
						<tr>
							<td>Price in €</td>
							<td id="summary-price"></td>
						</tr>
						<tr>
							<td>Manufacturer</td>
							<td id="summary-manufacturer"></td>
						</tr>
						<tr>
							<td>Wheel size in inch</td>
							<td id="summary-size"></td>
						</tr>
						<tr>
							<td>Framenumber</td>
							<td id="summary-framenumber"></td>
						</tr>
					</table>
				</div>
				<div class="row-fluid">

					<div id="summary-images" class="span6">
						<h4>Your Images</h4>

					</div>

					<div id="summary-components" class="span6">
						<h4>Bike Components</h4>

					</div>

				</div>

				<div class="row-fluid">
					<button class="btn btn-primary">Publish my report</button>
					<button class="btn">Publish and send to Facebook</button>
					<button class="btn btn-print">Print this summary</button>
				</div>

			</div>

		</div>
	</form>
</div>

<?php

		}
	}

?>
