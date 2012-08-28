<?php
require_once('config/config.php');
require_once('classes/user.php');
require_once('templates/headerBase.php');
require_once('classes/utils.php');

$reportID = false;
if (isset($_POST['reportID'])) $reportID = $_POST['reportID'];
if (isset($_GET['reportID'])) $reportID = $_GET['reportID'];
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
					<li><a href="index.php?action=me">My reports/hints</a></li>
					<li><a href="index.php?action=report">Add Report</a></li>
					<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Export<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="nav-header">
							choose format
						</li>
						<?php
							exportListLink('RDF/XML', 'rdf');
							exportListLink('RDF/JSON', 'json');
							exportListLink('N3/Turtle', 'ttl');
							exportListLink('N-Triples', 'txt');
						?>
						<li class="divider">
						</li>
						<li class="nav-header">
							Admin
						</li>
						<li>
							<a href="/setupGraph.php">Clear Graph</a>
						</li>
						<li>
							<a href="/index.php?action=deleteReport&amp;reportID=<?php echo $reportID; ?>">Delete this report</a>
						</li>
					</ul>
					</li>

					<?php } ?>
					<li>
						<a href="http://aksw.org/">Wiki</a>
					</li>
					<li>
						<a href="/index.php?action=help">Help</a>
					</li>

				</ul>
				<form action="index.php" method="get" class="navbar-search pull-left">
					<input type="hidden" name="action" value="search" />
					<input type="text" name="search" class="search-query" placeholder="Search" />
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
