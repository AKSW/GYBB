/*global module:false*/
module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({
		meta: {
			banner: '/*! getyourbikeback - a project by Julian Hesselbach | Last modified: ' +
				'<%= grunt.template.today("yyyy-mm-dd") %> */'
		},

		lint: {
			files: [
				'grunt.js',
				'js/jquery.bikemap.js',
				'js/main.js'
			]
		},

		concat: {
			libs: {
				// these files are already minified, so just concatenate them
				// and don't minify them later on
				src: [
					'<banner:meta.banner>',
					'js/jquery-1.7.2.min.js',
					'js/jquery.colorbox-min.js',
					'js/jquery.throttle-debounce.min.js',
					'js/jquery.tablesorter.min.js',
					'3rdparty/twitterBootstrap/js/bootstrap.min.js',
					'3rdparty/jquery-ui/js/jquery-ui-1.8.22.custom.min.js',
					'3rdparty/jquery-ui/js/jquery-ui.timepicker-1.0.1.min.js',
					'3rdparty/openlayers/OpenLayers-2.13dev.min.js'
				],
				dest: 'js/libs.min.js'
			},

			application: {
				src: [
					'js/jquery.bikemap.js',
					'js/main.js'
				],
				dest: 'js/application.js'
			},

			css: {
				src: [
					'css/style.css',
					'css/colorbox.css',
					'css/print.css'
				],
				dest: 'css/main.min.css'
			}
		},

		min: {
			application: {
				src: ['<banner:meta.banner>', '<config:concat.application.dest>'],
				dest: 'js/application.min.js'
			}
		},

		// jshint config options
		jshint: {
			options: {
				bitwise: true,
				browser: true,
				curly: true,
				eqeqeq: true,
				eqnull: true,
				immed: true,
				jquery: true,
				latedef: true,
				newcap: true,
				noarg: true,
				nonew: true,
				plusplus: true,
				regexp: false,
				trailing: true,
				undef: true
			}
		}

	});

	// registering default and deploy tasks -- default is used in watch too
	grunt.registerTask('default', 'lint concat min');

};
