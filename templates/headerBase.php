<!DOCTYPE html>
<html lang="de">
<head>
	<title>getyourbikeback</title>
	<meta charset="UTF-8" />
	<base href="<?php echo BASE_URL; ?>" />

<?php
if (DEBUG === true) {
?>
		<link rel="stylesheet" href="3rdparty/twitterBootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="3rdparty/jquery-ui/css/smoothness/jquery-ui-1.8.20.custom.css" />
		<link rel="stylesheet" href="3rdparty/openlayers/openlayers.css" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/colorbox.css" />
		<link rel="stylesheet" href="css/print.css" />

		<script src="js/debugging.js"></script>
		<script src="js/jquery-1.7.2.js"></script>
		<script src="js/jquery.colorbox-min.js"></script>
		<script src="3rdparty/twitterBootstrap/js/bootstrap.min.js"></script>
		<script src="3rdparty/jquery-ui/js/jquery-ui-1.8.20.custom.min.js"></script>
		<script src="3rdparty/jquery-ui/js/jquery-ui.timepicker.js"></script>
		<script src="3rdparty/openlayers/OpenLayers-2.12rc4.js"></script>

		<script type="text/javascript">
				jQuery.baseURL = "<?php echo BASE_URL; ?>";
		</script>
		<script src="js/jquery.bikemap.js"></script>
		<script src="js/main.js"></script>

<?php
} else {
	//TODO minified css and js
?>



<?php } ?>
</head>

