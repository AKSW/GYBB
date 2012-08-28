<?php
require_once('config/config.php');
require_once('classes/user.php');
require_once('templates/headerBase.php');
?>

<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a href="index.php?action=home" class="brand">Get your bike back</a>

				<ul class="nav">
					<li><a href="index.php?action=map">Explore</a></li>
					<?php  // add nav items for logged-in users
						if ($user = User::getCurrentUser()) { ?>
					<li><a href="index.php?action=report">Add Report</a></li>
					<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Export<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="nav-header">
							choose format
						</li>
						<li>
							<a href="#">RDF</a>
						</li>
						<li>
							<a href="#">Turtle</a>
						</li>
						<li>
							<a href="#">RDF/XML</a>
						</li>
						<li>
							<a href="#">RDF</a>
						</li>
						<li class="divider">
						</li>
						<li class="nav-header">
							Admin
						</li>
						<li>
							<a href="#">Save Graph</a>
						</li>
						<li>
							<a href="#">Clear Graph</a>
						</li>
					</ul>
					</li>
				</ul>

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
				<h2>CrowdInquiry</h2>
				<h3>The semantic option to get your bike back</h3>
			</div>
		</header>
		<section id="content" class="row-fluid">
