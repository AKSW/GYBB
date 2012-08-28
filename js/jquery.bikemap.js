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
 */

/*global OpenLayers:true*/
/*global searchResults:true*/
/*global console:true*/

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
		zoom: 4, //

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
			units: 'm'
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
		this.features = [];
		this.startLonLat = {};
		this.singleMarker = false;
		this.singleHintMarker = false;
		this.suggested = [];
		this.currentPopup = null;
    this.bounds = new OpenLayers.Bounds();

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
		},


		createMap: function() {
			// to successfully create a map, the container has to be visible
			var $parent = this.$mapElement.parent();
			this.$mapElement.appendTo($('body'));

			this.map = new OpenLayers.Map(this.mapElement,this.options.mapOptions);
			this.map.addLayer(new OpenLayers.Layer.OSM());
			this.zoomMapToCenter(this.options.startLon, this.options.startLat);
			this.newMarkersLayer();
			this.addMapFeatures();

			this.$mapElement.appendTo($parent);
		},


		addMapFeatures: function() {
			switch(this.options.mapType) {

				case 'exploration':
					// initially get all reports by doing a zoom/move event
					this.registerDefaultEvents();
					this._eventMapMoveZoomEnd(null);
					this.zoomToBestFit();
					break;

				case 'report':
					this.registerReportEvents();
					this.geolocate();
					break;

				case 'report-details':
					this.drawSingleMarker(this.options.startLon, this.options.startLat, this.options.reportState);
					this.registerDefaultEvents();
					break;

				case 'add-hint':
					this.drawSingleMarker(this.options.startLon, this.options.startLat, this.options.reportState);
					this.registerAddHintEvents();
					break;

				case 'hints':
					this.drawSingleMarker(this.options.startLon, this.options.startLat, this.options.reportState);
					this._eventMapMoveZoomEnd(null);
					this.zoomToBestFit();
					break;

				case 'searchresults':
					this.activateFacetedSearch();
					this.drawSearchMarkers(searchResults);
					this.zoomToBestFit();
					break;

				default:
					// initially get all reports by doing a zoom/move event
					this._eventMapMoveZoomEnd(null);
					this.zoomToBestFit();
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


		zoomToBestFit: function() {
			var that = this;
			// wrap in settimeout to wait for the response with the markers
			// one full second should be enough to get all markers
			window.setTimeout(function() {
				var center = that.bounds.getCenterLonLat();
				that.map.setCenter(center, that.map.getZoomForExtent(that.bounds));
			}, 1000);
		},


		drawMarkers: function(markers, showClosed, doNotRedraw) {
			var that = this;
			var size = new OpenLayers.Size(21, 25);
			var offset = new OpenLayers.Pixel(-(size.w / 2), -size.h);

			// every time before we draw markers reset the bounds
			// TODO?

			// dont create the map and markers newly if doNotRedraw is set to true
			if (doNotRedraw !== true) {
				that.newFeatures();
			}

			// draw each marker on the map
			$.each(markers, function() {

				// only add the marker and draw it if it is not 'closed'
				// and showClosed === true
				//
				if (this.state !== 'closed' || (this.state === 'closed' && showClosed === true)) {
					// check the color we want to draw depending on marker-type and status
					var image = that.getMarkerImage(this);
					var lonLat, feature, marker, combined, html;

					lonLat = new OpenLayers.LonLat(
						parseFloat(this.lon), parseFloat(this.lat)
					);
					lonLat.transform(
						new OpenLayers.Projection("EPSG:4326"),
						that.map.getProjectionObject()
					);

					// add the marker-lonLat to the bounds
					that.bounds.extend(lonLat);

					// create a feature with the given html
					feature = new OpenLayers.Feature(that.markersLayer, lonLat);
					feature.closeBox = true;

					feature.popupClass =	OpenLayers.Class(OpenLayers.Popup.Anchored, {
						'autoSize': true
					});

					feature.data.popupContentHTML = this.html;
					feature.data.overflow = "auto";
					feature.data.icon = new OpenLayers.Icon($.baseURL + '3rdparty/openlayers/img/' + image, size, offset);

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
				}
			});

		},


		getMarkerImage: function(marker) {
			var image;

			if (marker.type === 'report') {
				// now let's see - what state is it in?
				if (marker.state === 'resolved') {
					image = 'marker-gold.png';
				} else if (marker.state === 'closed') {
					image = 'marker-green.png';
				} else {
					image = 'marker.png'; // default
				}
			} else if (marker.type === 'hint') {
				image = 'marker-blue.png';
			}

			return image;
		},


		drawSearchMarkers: function(searchResults) {
			var markers = [];
			var newMarker;
			$.each(searchResults, function(key, value) {
				newMarker = {
					lon: this.lon,
					lat: this.lat,
					type: 'report',
					state: this.state,
					html: '<h3>' + this.bikeType + '</h3><p>' +
						'<strong>City:</strong><br /> ' + this.city + '<br />' +
						'<strong>Color:</strong><br /> ' + this.color + '<br /><br />' +
						'<a href="index.php?action=reportDetails&amp;reportID=' + key + '">Show Reportdetails</a>'
				};
				markers.push(newMarker);
			});

			this.drawMarkers(markers, true);
		},


		activateFacetedSearch: function() {
			var that = this;
			// only do sth if there is the faceted-serach form and at least one checkbox
			var $searchForm = $('#faceted-search');
			var $checkBoxes = $searchForm.find('input[type="checkbox"]');
			if ($searchForm.length && $checkBoxes.length) {
				$checkBoxes.on('click', function(e) {
					$searchForm.submit();
				});
				// and activate the more-link
				$('.show-more-factet-options').on('click', function(e) {
					e.preventDefault();
					var $link = $(this);
					if ($link.text() === 'More...') {
						$link.parent().find('.hidden')
							.toggleClass('hidden')
							.toggleClass('hidemeagain');
						$link.text('Less...');
					} else {
						$link.parent().find('.hidemeagain')
							.toggleClass('hidden')
							.toggleClass('hidemeagain');
						$link.text('More...');
					}
				});
			}
		},


		drawSingleMarker: function(lon, lat, state) {
			var that = this;
			var size = new OpenLayers.Size(21, 25);
			var offset = new OpenLayers.Pixel(-(size.w / 2), -size.h);
			var image = that.getMarkerImage({ state: state, type: 'report' });
			var icon = new OpenLayers.Icon($.baseURL + '3rdparty/openlayers/img/' + image, size, offset);

			if (this.singleMarker) {
				this.singleMarker.destroy();
			}
			this.singleMarker = new OpenLayers.Marker(
				new OpenLayers.LonLat(lon, lat).transform(
					this.options.mapOptions.displayProjection, this.options.mapOptions.projection
				), icon
			);
			// add the single marker to the bounds
			this.bounds.extend(new OpenLayers.LonLat(lon, lat).transform(
				this.options.mapOptions.displayProjection, this.options.mapOptions.projection
			));
			this.markersLayer.addMarker(this.singleMarker);
		},


		drawSingleHintMarker: function(lon, lat) {
			// the single hint marker is blue - use that icon
			var size = new OpenLayers.Size(21, 25);
			var offset = new OpenLayers.Pixel(-(size.w / 2), -size.h);
			// hints are _always_ blue -- dont check for color here
			var icon = new OpenLayers.Icon($.baseURL + '3rdparty/openlayers/img/marker-blue.png', size, offset);

			if (this.singleHintMarker) {
				this.singleHintMarker.destroy();
			}
			this.singleHintMarker = new OpenLayers.Marker(
				new OpenLayers.LonLat(lon, lat).transform(
					this.options.mapOptions.displayProjection, this.options.mapOptions.projection
				), icon
			);
			this.markersLayer.addMarker(this.singleHintMarker);
		},


		zoomMapToCenter: function(lon, lat) {
			var centerLonLat = new OpenLayers.LonLat(lon, lat);
			centerLonLat.transform(this.options.mapOptions.displayProjection, this.map.getProjectionObject());
			this.map.setCenter(centerLonLat, this.options.mapOptions.zoom);
		},


		// Basic event handling
		//==========================================================================

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
				that.zoomMapToCenter(e.position.coords.longitude, e.position.coords.latitude);
				that._reverseLonLatLookup(e.position.coords.longitude, e.position.coords.latitude);
			});

			$('.btn-findme').on('click', function(e) {
				e.preventDefault();
				geo.deactivate();
				geo.activate();
			});

		},


		registerDefaultEvents: function() {
			this.map.events.register('moveend', this, this._eventMapMoveZoomEnd);
			this.map.events.register('zomeend', this, this._eventMapMoveZoomEnd);
		},


		registerReportEvents: function() {
			var that = this;
			var address = this.$road.add(this.$postcode).add(this.$city);

			address.on('keyup', $.debounce(500, function(e) {
				e.preventDefault();
				// only do the lookup if there are more than 2-3 chars
				var roadLength = that.$road.val().length;
				var postcodeLength = that.$postcode.val().length;
				var cityLength = that.$city.val().length;
				if (roadLength > 3 || postcodeLength > 2 || cityLength > 3) {
					that._lonLatLookup();
				}
			}));

			this.map.events.register('click', this, this._eventMapClick);
		},


		registerAddHintEvents: function() {
			this.map.events.register('click', this, this._eventAddHintMapClick);
		},


		getReportsInArea: function(left, right, top, bottom) {
			// later on we will drawMarkers but choose not to redraw
			// the map -- so lets create the map and features here new:
			var that = this;
			// create a status-div for performance-checking hooks
			if (!$('#finished').length) {
				$('<div id="finished" class="hidden" />').appendTo('body');
			}

			$.getJSON($.baseURL + 'index.php', {
				'action': 'reportsInArea',
				'left': left,
				'right': right,
				'top': top,
				'bottom': bottom
			}, function(response) {
				var markers = [];
				var marker;

				$.each(response, function() {
					var report = this.reportID;
					marker = {
						lon: this.lon,
						lat: this.lat,
						type: 'report',
						state: this.state,
						// set the html for the marker from type/color etc.
						html: '<h3>' + this.bikeType + '</h3><p>' +
							'<strong>Color:</strong><br /> ' + this.color + '<br />' +
							'<strong>Noticed Theft:</strong><br /> ' + this.noticedTheft +
							'<br /><br />' + '<a href="index.php?action=reportDetails&reportID=' +
							report + '">Show Reportdetails</a>'
					};
					markers.push(marker);
				});
				that.drawMarkers(markers, false, true);
				$('#finished').addClass('finished-reports');

			});

			// now get all hints for all reports and draw them
			$.getJSON($.baseURL + 'index.php', {
				'action': 'hints'
			}, function(response) {
				var hints = [];
				var hint;

				$.each(response, function() {
					hint = {
						lon: this.lon,
						lat: this.lat,
						type: 'hint',
						html: '<h3>Hint by ' + this.hintUser + '</h3><p>' +
							'<strong>Time of observation:</strong><br /> ' + this.hintWhen + '<br />' +
							'<strong>Hint given on:</strong><br /> ' + this.created + '<br />' +
							'<strong>Hint:</strong><br />' + this.hintWhat +
							'<br /><br /><a href="index.php?action=reportDetails&amp;reportID=' + this.reportID + '">Show Reportdetails</a></p>'
					};
					hints.push(hint);
				});
				that.drawMarkers(hints, false, true);
				$('#finished').addClass('finished-hints');

			});
		},


		getHints: function(report) {
			var that = this;

			$.getJSON($.baseURL + 'index.php', {
				'action': 'hints',
				'reportID': report
			}, function(response) {
				var markers = [];
				var marker;

				$.each(response, function() {
					marker = {
						lon: this.lon,
						lat: this.lat,
						type: 'hint',
						html: '<h3>Hint by ' + this.hintUser + '</h3><p>' +
							'<strong>Time of observation:</strong><br /> ' + this.hintWhen + '<br />' +
							'<strong>Hint given on:</strong><br /> ' + this.created + '<br />' +
							this.hintWhat +
							'<br /><br /><a href="index.php?action=reportDetails&amp;reportID=' + report + '">Show Reportdetails</a></p>'
					};
					markers.push(marker);
				});
				that.drawMarkers(markers);

				// now if we have no singlehint set switch back to the first tab. boooo.
				$('#report-details-view').find('.nav-tabs li').removeClass('active');
				$('#report-details-view').find('.tab-pane').removeClass('active');

				if (that._getVariables('showHints') === undefined || that._getVariables('showHints') !== 'true') {
					$('#report-details-view').find('.nav-tabs li').first().addClass('active');
					$('#report-details-view').find('.tab-pane').first().addClass('active');
				} else {
					$('#report-details-view').find('.nav-tabs li').first().next().addClass('active');
					$('#report-details-view').find('.tab-pane').first().next().addClass('active');
				}
			});

		},


		// event callbacks
		//==========================================================================

		_eventMarkerMousedown: function(evt) {
			var that = this.that;
			var feature = this.feature;
			var popup = feature.createPopup(feature.closeBox);

			// case 1: we have no popup yet
			if (that.currentPopup === null) {
				// add the new popup to the map
				that.map.addPopup(popup);
				popup.show();
			} else { // if we have a popup check if it is the same
				if (that.currentPopup.id === popup.id && that.currentPopup.visible()) { // samesame -- hideit
					that.currentPopup.hide();
				} else { // or if it is a new one
					that.currentPopup.hide();
					that.currentPopup = null;
					that.map.addPopup(popup);
					popup.show();
				}
			}

			// set the current popup
			that.currentPopup = popup;
			OpenLayers.Event.stop(evt);
		},


		_eventMapMoveZoomEnd: function(evt) {
			var bounds = this.map.getExtent().transform(this.map.projection, this.map.displayProjection);

			switch(this.options.mapType) {
				case 'report':
					this.getReportsInArea(bounds.left, bounds.right, bounds.top, bounds.bottom);
					break;

				case 'exploration':
					this.getReportsInArea(bounds.left, bounds.right, bounds.top, bounds.bottom);
					break;

				case 'hints':
					this.getHints(this.options.report);
					break;

				default:
					break;
			}

		},


		_eventAddHintMapClick: function(evt) {
			var lonLat = this.map.getLonLatFromViewPortPx(evt.xy);
			lonLat.transform(this.map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
			this.drawSingleHintMarker(lonLat.lon, lonLat.lat);

			this.$lon.val(lonLat.lon);
			this.$lat.val(lonLat.lat);
		},


		_eventMapClick: function(evt) {
			var lonLat = this.map.getLonLatFromViewPortPx(evt.xy);
			lonLat.transform(this.map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
			this.drawSingleMarker(lonLat.lon, lonLat.lat, 'open');

			this.$lon.val(lonLat.lon);
			this.$lat.val(lonLat.lat);
			this._reverseLonLatLookup(lonLat.lon, lonLat.lat);
		},


		// private helper functions
		//==========================================================================

		_reverseLonLatLookup: function(lon, lat) {
			var that = this;
			$.getJSON(
				'http://nominatim.openstreetmap.org/reverse?format=json&lat=' +
				lat + '&lon=' + lon + '&zoom=18&addressdetails=1',
				function(place) {
					that._setAddressValues(place);
				}
			);
		},


		_lonLatLookup: function() {
			var that = this;
			var htmlout = '<h2>Did you mean...</h2>';

			var url = 'http://nominatim.openstreetmap.org/search/' + that.$postcode.val() + ' ' +
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
					that._setAddressValues(place);
					// set the marker on the map
					that.drawSingleMarker(place.lon, place.lat, 'open');
					// set map to center around new marker
					that.zoomMapToCenter(place.lon, place.lat);
				});
			});
		},


		_setAddressValues: function(place) {
			this.$road.val(place.address.road);
			this.$house_number.val(place.address.house_number);
			this.$postcode.val(place.address.postcode);
			this.$city.val(place.address.city);
			this.$lon.val(place.lon);
			this.$lat.val(place.lat);
		},


		// reading get variables with javascript
		_getVariables: function(id) {
			var vars = {};
			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
				vars[key] = value;
			});
			return vars[id];
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
}(jQuery, window, document));

