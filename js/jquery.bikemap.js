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
 *
 * jshint global OpenLayers: true
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
		zoom: 14,

		// and the default options for the map
		mapOptions: {
			theme: false, // not working with 2.12rc4
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
		}
	};


	// The actual plugin constructor
	function Plugin(mapElement, options) {
		// define some globally used domelements here
		this.$road = $('#road');
		this.$house_number = $('#house_number');
		this.$postcode = $('#postcode');
		this.$city = $('#city');
		this.$lon = $('#lon');
		this.$lat = $('#lat');
		this.$suggestions = $('#suggestions');

		// this will be filled up later
		this.map = {};
		this.markers = {};
		this.markersLayer = false;
		this.features = false;
		this.startLonLat = {};
		this.reportMarker = false;
		this.suggested = [];

		// default elements and options
		this.mapElement = mapElement;
		this.$mapElement = $(mapElement);
		this.options = $.extend({}, defaults, options);
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
			this.options.mapOptions = $.extend(this.options.mapOptions, this.options);
			this.createMap();
			this.addMapFeatures();
		},


		createMap: function() {
			this.map = new OpenLayers.Map(this.mapElement,this.options.mapOptions);
			this.map.addLayer(new OpenLayers.Layer.OSM());
			this.zoomMapToCenter(this.options.startLon, this.options.startLat);
			this.newMarkersLayer();
		},


		addMapFeatures: function() {
			switch(this.options.mapType) {

				case 'exploration':
					// initially get all reports by doing a zoom/move event
					this.registerExplorationEvents();
					this._eventMapMoveZoomEnd(null);
					break;

				case 'report':
					this.registerReportEvents();
					this.geolocate();
					break;

				default:
					// initially get all reports by doing a zoom/move event
					this._eventMapMoveZoomEnd(null);
					break;
			}
		},


		newMarkersLayer: function() {
			if (!this.markersLayer) {
				this.createMarkersLayer();
			} else {
        this.markersLayer.destroy();
				this.createMarkersLayer();
			}
			this.map.addLayer(this.markersLayer);
		},


		createMarkersLayer: function() {
			this.markersLayer = new OpenLayers.Layer.Markers("Reports", {
				projection: new OpenLayers.Projection("EPSG:4326"),
				visibility: true,
				displayInLayerSwitcher: false
			});
		},


		newFeatures: function() {
			var that = this;
			if (that.features) {
				$.each(that.features, function() {
					this.destroy();
					this.destroyPopup();
				});
			}
			that.features = [];
		},


		drawMarkers: function(markers) {
			var that = this;
			that.newMarkersLayer();
			that.newFeatures();

			// draw each marker on the map
			$.each(markers, function() {
				var lonLat, feature, marker, combined, html;

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

				feature.popupClass =	OpenLayers.Class(OpenLayers.Popup.Anchored, {
					'autoSize': true
				});

				// set the html for the marker from type/color etc.
				html = '<h2>' + this.bikeType + '</h2><p>Color: ' + this.color;
				html += '<br />Date of Theft: ' + this.dateOfTheft;
				html += '<br /><a href="index.php?action=reportDetails&reportID=' + this.reportID + '">Show Reportdetails</a>';

				feature.data.popupContentHTML = html;
				feature.data.overflow = "auto";

				// create a marker from that feature, add click events and add it to the map
				marker = feature.createMarker();
				// we need a reference to this feature AND this plugin
				combined = {
					that: that,
					feature: feature
				};
				marker.events.register("mousedown", combined, that._eventMarkerMousedown);
				that.markersLayer.addMarker(marker);

				// initialize the popup and hide it
				feature.popup = feature.createPopup(feature.closeBox);
				that.map.addPopup(feature.popup);
				feature.popup.hide();

				// add the created feature to the array of features
				that.features.push(feature);
			});

		},


		drawSingleMarker: function(lon, lat) {
			if (this.reportMarker) {
				this.reportMarker.destroy();
			}
			this.reportMarker = new OpenLayers.Marker(
				new OpenLayers.LonLat(lon, lat).transform(
					this.options.mapOptions.displayProjection, this.options.mapOptions.projection
				)
			);
			this.markersLayer.addMarker(this.reportMarker);
		},


		zoomMapToCenter: function(lon, lat) {
			var centerLonLat = new OpenLayers.LonLat(lon, lat);
			centerLonLat.transform(this.options.mapOptions.displayProjection, this.map.getProjectionObject());
			this.map.setCenter(centerLonLat, this.options.mapOptions.zoom);
		},


		// Basic event handling
		//==========================================================================

		// TODO this is not working yet
		geolocate: function() {
			var that = this;
			var geo = new OpenLayers.Control.Geolocate({
				bind: false,
				geolocationOptions: {
					enableHighAccuracy: false,
					maximumAge: 0,
					timeout: 7000
				}
			});

			that.map.addControl(geo);
			geo.events.register("locationupdated", geo, function(e) {
				// TODO in the future? set road city etc. see lookup
				that.drawSingleMarker(e.position.coords.longitude, e.position.coords.latitude);
				that.zoomMapToCenter(e.position.coords.longitude, e.position.coords.latitude);
				that._reverseLonLatLookup(e.position.coords.longitude, e.position.coords.latitude);
			});

			$('.btn-findme').on('click', function(e) {
				e.preventDefault();
				geo.deactivate();
				geo.activate();
			});

		},


		registerExplorationEvents: function() {
			this.map.events.register('moveend', this, this._eventMapMoveZoomEnd);
			this.map.events.register('zomeend', this, this._eventMapMoveZoomEnd);
		},


		registerReportEvents: function() {
			var that = this;
			var address = this.$road.add(this.$postcode).add(this.$city);

			address.on('keyup', function(e) {
				// TODO wait 1-2 seconds before firing evt.
				e.preventDefault();
				that._lonLatLookup();
			});

			this.map.events.register('click', this, this._eventMapClick);
		},


		getReportsInArea: function(left, right, top, bottom) {
			var that = this;

			$.getJSON($.baseURL + 'index.php', {
				'action': 'reportsInArea',
				'left': left,
				'right': right,
				'top': top,
				'bottom': bottom
			}, function(response) {
				that.drawMarkers(response);
			});

		},


		// event callbacks
		//==========================================================================

		_eventMarkerMousedown: function(evt) {
			var currentPopup; // TODO not needed?
			if (this.feature.popup === null) {
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
			this.getReportsInArea(bounds.left, bounds.right, bounds.top, bounds.bottom);
		},


		_eventMapClick: function(evt) {
			var lonLat = this.map.getLonLatFromViewPortPx(evt.xy);
			lonLat.transform(this.map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
			this.drawSingleMarker(lonLat.lon, lonLat.lat);

			this.$lon.val(lonLat.lon);
			this.$lat.val(lonLat.lat);
			this._reverseLonLatLookup(lonLat.lon, lonLat.lat);
		},


		// private helper functions
		//==========================================================================

		_reverseLonLatLookup: function(lon, lat) {
			var that = this;
			$.getJSON(
				'http://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lon + '&zoom=18&addressdetails=1',
				function(data) {
					that.$road.val(data.address.road);
					that.$house_number.val(data.address.house_number);
					that.$postcode.val(data.address.postcode);
					that.$city.val(data.address.city);
				}
			);

		},


		_lonLatLookup: function() {
			var that = this;
			var htmlout = '<h2>Did you mean...</h2>';

			// TODO hardcoded GERMANY... /de/ - remove this?
			var url = 'http://nominatim.openstreetmap.org/search/de/' + that.$postcode.val() + ' ' +
					that.$city.val() + '/' + that.$road.val() + '/' + that.$house_number.val() +
					'?format=json&polygon=1&addressdetails=1&osm_type=N';
			url = encodeURI(url);
			$.getJSON(url, function(data) {
				that.suggested = [];

				$.each(data, function() {
					that.suggested[this.place_id] = this;
					htmlout += '<a class="suggest-link" href="place_id#' + this.place_id + '">' + this.display_name + '</a><br />';
				});

				that.$suggestions.html(htmlout);
				$('.suggest-link').on('click', function(e) {
					e.preventDefault();
					var placeID = $(this).attr('href').replace('place_id#', '');
					var place = that.suggested[placeID];

					// TODO refactor! see _reverseLonLatLookup
					that.$road.val(place.address.road);
					that.$house_number.val(place.address.house_number);
					that.$postcode.val(place.address.postcode);
					that.$city.val(place.address.city);
					that.$lon.val(place.lon);
					that.$lat.val(place.lat);

					// set the marker on the map
					that.drawSingleMarker(place.lon, place.lat);

					// set map to center around new marker
					that.zoomMapToCenter(place.lon, place.lat);

				});
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
	};
})(jQuery, window, document);

