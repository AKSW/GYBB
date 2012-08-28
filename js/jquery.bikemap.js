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

    // and the default options for the map
    mapOptions: {
      scales: [50000000, 30000000, 10000000, 5000000],
      projection: new OpenLayers.Projection("EPSG:900913"),
      displayProjection: new OpenLayers.Projection("EPSG:4326"),
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
    this.map = {};
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
      this.options.mapType = this.$mapElement.data('bikemaptype');
      this.createMap();

      console.log(this.options);


    },

    createMap: function() {
      this.map = new OpenLayers.Map(this.mapElement, this.options.mapOptions);

    },

    addMapLayers: function() {
      var featureLayer = new OpenLayers.Layer.Markers("Reports", {
        projection: new OpenLayers.Projection("EPSG:4326"),
        visibility: true,
        displayInLayerSwitcher: false
      });
      this.map.addLayer(new OpenLayers.Layer.OSM());
      this.map.addLayer(featureLayer);


    },


    _privateFunction: function() {
      console.log('Private function');

    }

  });

  //  plugin wrapper around the constructor, preventing against multiple instantiations
  //  and allowing access to public functions (non-'_'-prefixed)
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
