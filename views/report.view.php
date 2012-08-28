<?php
require_once "views/base.view.php";

  class ReportView extends BaseView {


    protected function doShow() {

//TODO: get post parameter benoetigt?
?>

<div class="row-fluid">
  <form method="post" action="index.php?action=report">
    <input type="hidden" name="action" value="report" />

    <ul class="nav nav-tabs" id="nav-report">
      <li class="">
        <a href="#date-and-time"><span class="badge">1</span> Date and time</a>
      </li>
      <li class="active">
        <a href="#location"><span class="badge">2</span> Location</a>
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

      <div class="tab-pane " id="date-and-time">
        <fieldset>
          <legend>Angaben zum Tatzeitraum</legend>
          <ul>
            <li>
              <label for="dateoftheft">Stehldatum</label>
              <input type="text" id="dateoftheft" name="dateoftheft"/>
            </li>
          </ul>
        </fieldset>
      </div>

      <div class="tab-pane active" id="location">
        <fieldset class="row-fluid">
          <legend>Angaben zum Ort</legend>
          <ul class="span4">
            <li>
              <label for="street">Straße</label>
              <input type="text" id="street" name="street"/>
            </li>
            <li>
              <label for="housenumber">Hausnummer</label>
              <input type="text" id="housenumber" name="housenumber"/>
            </li>
            <li>
              <label for="areacode">Postleitzahl</label>
              <input type="text" id="areacode"  name="areacode"/>
            </li>
            <li>
              <label for="city">Ort</label>
              <input type="text" id="city" name="city"/>
            </li>
            <li>
              <label for="lon">Longitude</label>
              <input type="text" id="lon" name="lon"/>
            </li>
            <li>
              <label for="lat">Latitude</label>
              <input type="text" id="lat" name="lat"/>
            </li>
          </ul>

          <div class="span8">
            <div class="bikemap bikemap-report" data-bikemaptype="report"></div>
          </div>

        </fieldset>

      </div>

      <div class="tab-pane" id="your-bike">

        <fieldset>
          <legend>Angaben zum Fahrrad</legend>
          <ul>
            <li>
              <label for="biketype">Fahrradart</label>
              <input type="text" id="biketype" name="biketype" />
            </li>
            <li>
              <label for="color">Farbe</label>
              <input type="text" id="color" name="color"/>
            </li>
            <li>
              <label for="description">Beschreibung</label>
              <textarea rows="5" col="50" id="description" name="description"> </textarea>
            </li>
            <li>
            <div>
              <fieldset>
                <legend>zusätzliche Komponenten</legend>
                <ul>
                  <li>

                    <label for="comptyp-1">Art</label><input type="text" name="comptyp-1" id="typ-1" />
                    <label for="compname-1">Name</label><input type="text" name="compname-1" id="name-1" />
                  </li>

                  <li>
                    <label for="comptyp-2">Art</label><input type="text" name="comptyp-2" id="typ-2" />
                    <label for="compname-2">Name</label><input type="text" name="compname-2" id="name-2" />
                  </li>
                </ul>
              </fieldset>
            </div>
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

        </fieldset>
      </div>

      <div class="tab-pane" id="image-upload">
        <fieldset>
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
        <p>
          All data from before
        </p>
        <input type="submit" value="Publish my report" />
        <input type="submit" value="Publish and send to Facebook" />
        <button class="btn btn-print">Print this summary</button>
      </div>

    </div>
  </form>
</div>

<?php

    }
  }

?>
