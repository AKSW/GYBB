<?php
require_once('config/config.php');
require_once('classes/user.php');
?>

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
		<link rel="stylesheet" href="css/style.css" />

		<script src="js/debugging.js"></script>
		<script src="js/jquery-1.7.2.js"></script>
		<script src="3rdparty/twitterBootstrap/js/bootstrap.min.js"></script>
		<script src="3rdparty/jquery-ui/js/jquery-ui-1.8.20.custom.min.js"></script>
		<script src="3rdparty/jquery-ui/js/jquery-ui.timepicker.js"></script>
		<script src="js/OpenLayers-2.11.js"></script>

		<script type="text/javascript">
				jQuery.baseURL = "<?php echo BASE_URL; ?>";
		</script>
		<script src="js/jquery.bikemap.js"></script>
		<script src="js/main.js"></script>

	<?php
		} else {
		// TODO the minified css does not exist yet
	?>
		<link rel="stylesheet" href="css/main.min.css" />
	<?php
		}
	?>

</head>

<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a href="index.php?action=home" class="brand">Get your bike back</a>

				<ul class="nav">
					<li><a href="index.php?action=map">Map</a></li>
					<?php  // add nav items for logged-in users
						if ($user = User::getCurrentUser()) { ?>
					<li><a href="index.php?action=report">Add Report</a></li>
					<?php } ?>

				</ul>
				<form action="index.php?action=search" class="navbar-search pull-left">
					<input type="text" class="search-query" placeholder="Search" />
				</form>

				<ul class="nav pull-right">
					<li>
					<?php
						if ($user = User::getCurrentUser()) {
							print '<a href="index.php?action=logout">Logged in as: ' . $user->name . ' | Logout</a>';
						} else {
							print '<a href="index.php?action=facebook" class="ir btn-facebook"></a>';
						}
					?>
					</li>
				</ul>

			</div>
		</div>
	</div>
	<div class="container-fluid">
		<header id="header" class="row-fluid">


			<h1 class="span3"><a href="index.php?action=home" class="ir logo">Get your bike back</a></h1>
			<div class="span9">
				<h2>Crowdsourcing</h2>
				<h3>The semantic option to get your bike back</h3>
			</div>
		</header>
		<section id="content" class="row-fluid">
