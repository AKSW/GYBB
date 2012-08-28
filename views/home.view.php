<?php
require_once('views/base.view.php');
require_once('config/config.php');

	class HomeView extends BaseView {

		private $recentlyStolen;

		function __construct($recentlyStolen)  {
			$this->recentlyStolen = $recentlyStolen;

		}

		protected function doShow() { ?>
			<div class="span12">

				<header class="clearfix">
					<h2 class="pull-left">Welcome!</h2>
					<button class="btn btn-home-boxes pull-right">Toggle Infoboxes</button>
				</header>

				<section id="welcome">
					<div class="bikemap bikemap-home" data-bikemaptype="exploration"></div>

					<section class="sidebar sidebar-main">
						<article class="recently-stolen box">
							<h2>Recently stolen</h2>
							<ol>
							<?php
								if (is_array($this->recentlyStolen) && !empty($this->recentlyStolen))  {
									foreach ($this->recentlyStolen as $singleStolen) { ?>
										<li id="<?php echo $singleStolen['bikeID']; ?>" class="stolen-bike">
											<a href="<?php echo BASE_URL ?>index.php?action=reportDetails&reportID=<?php echo $singleStolen['reportID']; ?>">
											<?php echo readableDateTime($singleStolen['noticedTheft']) . ', ' . $singleStolen['city'] . ' - ' . $singleStolen['bikeType']; ?>
											</a>
											<?php if (isset($singleStolen['image'])) { ?>
											<div class="hidden stolen-image">
												<img src="/3rdparty/timthumb/timthumb.php?src=<?php echo $singleStolen['image']; ?>&w=200" alt="" />
											</div>
											<?php } ?>
										</li>
									<?php
									}
								}
								?>
							</ol>
						</article>

						<article class="newest-hints box">
							<h2>Newest Hints</h2>
							<ul>
								<li> <a href="#">Hinthint</a> </li>
								<li> <a href="#">Hinthint</a> </li>
								<li> <a href="#">Hinthint</a> </li>
								<li> <a href="#">Hinthint</a> </li>
							</ul>
						</article>
					</section>

					<section class="sidebar sidebar-second">
						<nav class="nav-other box">
							<h3>More</h3>
							<ul>
								<li><a href="index.php?action=faq">FAQ</a></li>
								<li><a href="index.php?action=about">About</a></li>
								<li><a href="index.php?action=project">Project</a></li>
							</ul>
						</nav>

						<article class="stats box">
							<h3>Stats</h3>
							<table>
								<tr>
									<td>Bikes returned</td>
									<td>4</td>
								</tr>
								<tr>
									<td>Bikes stolen</td>
									<td>897234</td>
								</tr>
								<tr>
									<td>Hints given</td>
									<td>73</td>
								</tr>
							</table>
						</article>
					</section>


					<a href="index.php?action=map" id="btn-start" class="btn btn-large btn-primary">
						Start <i class="icon-white icon-chevron-right"></i>
					</a>

				</section>
			<?php

		}
  }

?>
