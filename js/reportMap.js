function ReportMap(optionParams) {




  function reverseLookup(lon, lat) {
    $.getJSON(
      'http://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lon + '&zoom=18&addressdetails=1',
      function(data) {
        // TODO refactor -- save as variables for reusage
        $('#street').val(data.address.road);
        $('#areacode').val(data.address.postcode);
        $('#city').val(data.address.city);
      }
    );

  }


  // TODO lookup if plz + Street etc. are set
  function lookupLonLat() {
    $('#street, #city, #areacode').on('keyup', function(e) {
      e.preventDefault();

      var city = $('#city').val();
      var road = $('#street').val();
      var areacode = $('#areacode').val();
      var house = $('#housenumber').val();

      // hardcoded GERMANY TODO
      var url = 'http://nominatim.openstreetmap.org/search/de/' + areacode + ' '
              + city + '/' + road + '?format=json&polygon=1&addressdetails=1';
      url = encodeURI(url);
      $.getJSON(url, function(data) {
        console.log(data);
      });

    });
  }
  lookupLonLat();


  //get gps coordinates from click and move marker there
  function handleMapClick(evt) {
    var coord = map.getLonLatFromViewPortPx(evt.xy);
    coord.transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));

    var lonLat = map.getLayerPxFromViewPortPx(evt.xy) ;
    marker.map = map ;
    marker.moveTo(lonLat) ;

    $('#lon').val(coord.lon);
    $('#lat').val(coord.lat);


    reverseLookup(coord.lon, coord.lat);
  };

  //Set start centrepoint and zoom
  var lonLat = new OpenLayers.LonLat(12.38050700, 51.34384400).transform(
    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
    map.getProjectionObject() // to Spherical Mercator Projection
  );
  var zoom = 11;

  //set marker per click
  var markers = new OpenLayers.Layer.Markers("Markers");
  marker = new OpenLayers.Marker(lonLat) ;
  markers.addMarker(marker);
  map.addLayer(markers);

  //register clickhandler for fetching coordinates
  map.events.register('click', map, handleMapClick);


  this.map = map;
  this.featureLayer = featureLayer;
  var reportLoader = new ReportLoader();

  map.events.register('moveend', this, reportLoader.eventHandler);
  map.events.register('zomeend', this, reportLoader.eventHandler);
  map.setCenter(lonLat, zoom);

}; // end ReportMap





 function ReportLoader() {

  //helper function to construct uri
  var constructURI = function(baseURL, bounds) {
      return baseURL + "?x0=" + bounds.left + "&y0=" + bounds.top + "&x1=" + bounds.right + "&y1=" + bounds.bottom;
  }

  this.eventHandler = function (evt) {
    console.log("Handle event");
    var map = this.map;
    var bounds = map.getExtent().transform(map.projection,map.displayProjection);
    console.log(constructURI(this.baseURL, bounds));
  }
};


/*
 * Erwartet ein feature mit lat, lon
 */
ReportMap.prototype.addFeature = function(rawFeature) {

  var lonLat = new OpenLayers.LonLat(parseFloat(rawFeature.lon), parseFloat(rawFeature.lat));

  lonLat = lonLat.transform(
    this.map.displayProjection, this.map.projection
  );

  var size = new OpenLayers.Size(21, 25);
  console.log(size);
  var offset = new OpenLayers.Pixel( -(size.w / 2), -size.h);
  var icon = new OpenLayers.Icon("/img/marker-blue.png", size, offset);

  var feature = new OpenLayers.Feature(this.featureLayer, lonLat, {icon: icon});
  feature.popupClass = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {
    'panMapIfOutOfView': false,
    'autoSize': true
  });
  var marker = feature.createMarker();

  this.featureLayer.addMarker(marker);
  this.featureLayer.addMarker(new OpenLayers.Marker(
    new OpenLayers.LonLat(12.38050700, 51.34384400 ).transform(
      this.map.displayProjection, this.map.projection
  )));

};



