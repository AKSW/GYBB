/*!
 * jQuery Bikemap Plugin
 * Author: Julian Hesselbach
 *
 * Use like this:
 * $('#map').bikemap({ option: 'value' });
 * $('#map').bikemap('publicFunction', arg1, arg2);
 *
 * Set type of map by specifiying the 'data-bikemaptype' attribute
 * in the element to be the map, e.g.:
 *
 * <div id="bikemap" data-bikemaptype="exploration"></div>
 */
;(function($, window, document, undefined) {

	// set your default options
	var pluginName = 'bikemap';
	var defaults = {
		// set your default options here
		version: '0.1',
		baseURL: $.baseURL,
		mapType: 'exploration',
		startLon: 12.38050700,
		startLat: 51.34384400,

		// and the default options for the map
		mapOptions: {
			theme: null,
			zoom: 13,
			scales: [50000000, 30000000, 10000000, 5000000],
			projection: new OpenLayers.Projection("EPSG:900913"),
			displayProjection: new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
			resolutions: [1.40625, 0.703125, 0.3515625, 0.17578125, 0.087890625, 0.0439453125],
			minScale: 50000000,
			maxResolution: "auto",
			maxExtent: new OpenLayers.Bounds(-180, -90, 180, 90),
			maxScale: 10000000,
			minResolution: "auto",
			minExtent: new OpenLayers.Bounds(-1, -1, 1, 1),
			numZoomLevels: 19,
			units: 'm',
			controls: [
				new OpenLayers.Control.MouseDefaults(),
				new OpenLayers.Control.PanZoomBar(),
				new OpenLayers.Control.MousePosition(),
				new OpenLayers.Control.ScaleLine(),
			],
		}
	};


	// The actual plugin constructor
	function Plugin(mapElement, options) {
		// define some globally used domelements here
		this.$inputRoad = $('#road');
		this.$inputPostcode = $('#postcode');
		this.$inputCity = $('#city');
		this.$inputLon = $('#lon')
		this.$inputLat = $('#lat')

		// this will be filled up later
		this.map = {};
		this.markers = {};
		this.markersLayer = {};
		this.startLonLat = {};
		this.reportMarker = false;

		// default elements and options
		this.mapElement = mapElement;
		this.$mapElement = $(mapElement);
		this.options = $.extend({}, defaults, options) ;
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	// all plugin methods go here - prepend '_' for private functions
	// ================================================================================
	// ================================================================================
	$.extend(Plugin.prototype, {

		// the default initalization-function
		init: function () {
			// get the current maptype from the element and store it for later use
			this.options.mapType = this.$mapElement.data('bikemaptype');

			this.createMap();
			this.addMapFeatures();
		},


		createMap: function() {
			this.map = new OpenLayers.Map(this.mapElement,this.options.mapOptions);
			this.map.addLayer(new OpenLayers.Layer.OSM());

			this.startLonLat = new OpenLayers.LonLat(this.options.startLon, this.options.startLat);
			this.startLonLat.transform(new OpenLayers.Projection("EPSG:4326"), this.map.getProjectionObject());

			this.map.setCenter(this.startLonLat, this.options.mapOptions.zoom);

			this.markersLayer = new OpenLayers.Layer.Markers("Reports", {
				projection: new OpenLayers.Projection("EPSG:4326"),
				visibility: true,
				displayInLayerSwitcher: false
			});

			this.map.addLayer(this.markersLayer);
		},


		addMapFeatures: function() {
			switch(this.options.mapType) {
				case 'exploration':
					// TODO this is just for testing
					this.drawMarkers([
						{ lat: 51.34384400, lon: 12.37050700, html: '<h2>Report, yay</h2><p>Testtest</p><p>More test... <a href="#">Link</a>' },
						{ lat: 51.34384400, lon: 12.38050700, html: '<h2>Report, 2</h2><p>Testtest</p><p>More test... <a href="#">Link</a>' },
						{ lat: 51.34384400, lon: 12.39050700, html: '<h2>Report, 3</h2><p>Testtest</p><p>More test... <a href="#">Link</a>' }
					]);
					this.registerMapEvents();
					break;

				case 'report':
					this.registerMapEvents();
					this.registerReportEvents();
					break;

				// TODO more cases?

				default:
					// TODO maybe the same as exploration?!
					break;
			}
		},


		drawMarkers: function(markers) {
			var that = this;

			// TODO use this for custom markers/features?
			// var size = new OpenLayers.Size(21, 25);
			// console.log(size);
			// var offset = new OpenLayers.Pixel( -(size.w / 2), -size.h);
			// var icon = new OpenLayers.Icon("/img/marker-blue.png", size, offset);

			// draw each marker on the map
			$.each(markers, function() {
				var lonLat, feature, marker, combined;

				lonLat = new OpenLayers.LonLat(
					parseFloat(this.lon), parseFloat(this.lat)
				);
				lonLat.transform(
					new OpenLayers.Projection("EPSG:4326"),
					that.map.getProjectionObject()
				);

				// create a feature with the given html
				feature = new OpenLayers.Feature(that.markersLayer, lonLat);
				feature.closeBox = true;
				feature.popupClass =	OpenLayers.Class(OpenLayers.Popup.FramedCloud, {
					'autoSize': true,
					'maxSize': new OpenLayers.Size(300,200)
				});
				feature.data.popupContentHTML = this.html;
				feature.data.overflow = "auto";

				// create a marker from that feature, add click events and add it to the map
				marker = feature.createMarker();
				// we need a reference to this feature AND this plugin
				combined = {
					that: that,
					feature: feature
				}
				marker.events.register("mousedown", combined, that._eventMarkerMousedown);
				that.markersLayer.addMarker(marker);

				// initialize the popup and hide it
				feature.popup = feature.createPopup(feature.closeBox);
				that.map.addPopup(feature.popup);
				feature.popup.hide();
			});
		},


		// Basic event handling
		//==========================================================================

    // TODO this is not working yet
		geolocate: function() {
			var geolocate = new OpenLayers.Control.Geolocate({
				bind: false,
				geolocationOptions: {
					enableHighAccuracy: false,
					maximumAge: 0,
					timeout: 7000
				}
			});

			map.addControl(geolocate);
			geolocate.events.register("locationupdated", geolocate, function(e) {
				map.zoomToExtent(vector.getDataExtent());
				firstGeolocation = false;
				this.bind = true;
			});

			$('.btn-findme').on('click', function(e) {
				geolocate.deactivate();
				geolocate.watch = false;
				geolocate.activate();
			});

		},


/////////////////////////////////////


		registerMapEvents: function() {
			this.map.events.register('moveend', this, this._eventMapMoveZoomEnd);
			this.map.events.register('zomeend', this, this._eventMapMoveZoomEnd);
		},


		registerReportEvents: function() {
			var that = this;
			var address = this.$inputRoad.add(this.$inputPostcode).add(this.$inputCity);

			address.on('keyup', function(e) {
				// TODO wait 1-2 seconds before firing evt.
				e.preventDefault();
				that._lonLatLookup();
			})

			this.map.events.register('click', this, this._eventMapClick);
		},


		// event callbacks
		//==========================================================================

		_eventMarkerMousedown: function(evt) {
			if (this.feature.popup == null) {
				this.feature.popup = this.feature.createPopup(this.feature.closeBox);
				this.that.map.addPopup(this.feature.popup);
				this.feature.popup.show();
			} else {
				this.feature.popup.toggle();
			}
			currentPopup = this.feature.popup;
			OpenLayers.Event.stop(evt);
		},


		_eventMapMoveZoomEnd: function(evt) {
			var bounds = this.map.getExtent().transform(this.map.projection, this.map.displayProjection);
			// TODO do sth usefuel with the bounds data here
			log(this._constructURI($.baseURL, bounds));
		},


		_eventMapClick: function(evt) {
			var coord = this.map.getLonLatFromViewPortPx(evt.xy);
			var lonLat = this.map.getLayerPxFromViewPortPx(evt.xy);

			coord.transform(this.map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));

			// if there is no marker, create one
			// TODO this does not work properly -- marker is set but not shown
			if (!this.reportMarker) {
				this.reportMarker = new OpenLayers.Marker(lonLat);
				this.markersLayer.addMarker(this.reportMarker);

			} else { // just move it
				this.reportMarker.moveTo(lonLat);
			}

			this.$inputLon.val(coord.lon);
			this.$inputLat.val(coord.lat);
			this._reverseLonLatLookup(coord.lon, coord.lat);
		},


		// private helper functions
		//==========================================================================

		_constructURI: function(baseURL, bounds) {
			return baseURL + "?x0=" + bounds.left + "&y0=" + bounds.top + "&x1=" + bounds.right + "&y1=" + bounds.bottom;
		},


		_reverseLonLatLookup: function(lon, lat) {
			var that = this;
			$.getJSON(
				'http://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lon + '&zoom=18&addressdetails=1',
				function(data) {
					that.$inputRoad.val(data.address.road);
					that.$inputPostcode.val(data.address.postcode);
					that.$inputCity.val(data.address.city);
				}
			);

		},


		_lonLatLookup: function() {
			var city = this.$inputCity.val();
			var road = this.$inputRoad.val();
			var postcode = this.$inputPostcode.val();

			// TODO hardcoded GERMANY... /de/
			var url = 'http://nominatim.openstreetmap.org/search/de/' + postcode + ' '
							+ city + '/' + road + '?format=json&polygon=1&addressdetails=1';
			url = encodeURI(url);
			$.getJSON(url, function(data) {

				// TODO do sth. useful if the data is useful
				console.log(data);
			});
		},


		_privateFuntion: function() {
		}



	}); // end Plugin.prototype extend

	//==========================================================================
	//==========================================================================
	//	plugin wrapper around the constructor, preventing against multiple instantiations
	//	and allowing access to public functions (non-'_'-prefixed)
	$.fn[pluginName] = function(options) {
		var args = arguments;
		if (options === undefined || typeof options === 'object') {
			return this.each(function() {
				if (!$.data(this, 'bikemap_' + pluginName)) {
					$.data(this, 'bikemap_' + pluginName, new Plugin(this, options));
				}
			});
		} else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
			return this.each(function() {
				var instance = $.data(this, 'bikemap_' + pluginName);
				if (instance instanceof Plugin && typeof instance[options] === 'function') {
					instance[options].apply(instance, Array.prototype.slice.call(args, 1));
				}
			});
		}
	}
})(jQuery, window, document);

