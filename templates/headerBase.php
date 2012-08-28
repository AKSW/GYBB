<!DOCTYPE html>
<html lang="de">
<head>
	<title>getyourbikeback</title>
	<meta charset="UTF-8" />
	<base href="<?php echo WEB_BASE_URL; ?>" />

	<?php if (DEBUG === true) : ?>

	<link rel="stylesheet" href="3rdparty/twitterBootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="3rdparty/twitterBootstrap/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="3rdparty/jquery-ui/css/smoothness/jquery-ui-1.8.22.custom.css" />
	<link rel="stylesheet" href="3rdparty/openlayers/openlayers.css" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/colorbox.css" />
	<link rel="stylesheet" href="css/print.css" />

	<script src="js/debugging.js"></script>

	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/jquery.colorbox-min.js"></script>
	<script src="js/jquery.throttle-debounce.min.js"></script>
	<script src="js/jquery.tablesorter.min.js"></script>
	<script src="3rdparty/twitterBootstrap/js/bootstrap.min.js"></script>
	<script src="3rdparty/jquery-ui/js/jquery-ui-1.8.22.custom.min.js"></script>
	<script src="3rdparty/jquery-ui/js/jquery-ui.timepicker-1.0.1.min.js"></script>
	<script src="3rdparty/openlayers/OpenLayers-2.13dev.min.js"></script>

	<script>
		jQuery.baseURL = "<?php echo WEB_BASE_URL; ?>";
	</script>
	<script src="js/jquery.bikemap.js"></script>
	<script src="js/main.js"></script>

	<?php else :
	// minified css and js -- for the build process see grunt.js
	// we include the third-party css withouth concatenating them
	// because they include images from relatively to their location ?>
	<link rel="stylesheet" href="3rdparty/twitterBootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="3rdparty/twitterBootstrap/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="3rdparty/jquery-ui/css/smoothness/jquery-ui-1.8.22.custom.css" />
	<link rel="stylesheet" href="3rdparty/openlayers/openlayers.css" />
	<link rel="stylesheet" href="css/main.min.css" />
	<script src="js/libs.min.js"></script>
	<script>
		jQuery.baseURL = "<?php echo WEB_BASE_URL; ?>";
	</script>
	<script src="js/application.min.js"></script>

	<?php endif; ?>
</head>
