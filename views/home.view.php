<?php
require_once('views/base.view.php');
require_once('config/config.php');

class HomeView extends BaseView {

	private $recentlyStolen;
	private $hints;

	function __construct($recentlyStolen, $hints)  {
		$this->hints = $hints;
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
		<article class="recently-stolen box clearfix">
			<h2>Recently stolen</h2>
			<ol>
			<?php
				if (is_array($this->recentlyStolen) && !empty($this->recentlyStolen))  {
					foreach ($this->recentlyStolen as $singleStolen) { ?>
						<li id="<?php echo $singleStolen['bikeID']; ?>" class="stolen-bike">
							<a href="<?php echo WEB_BASE_URL ?>index.php?action=reportDetails&amp;reportID=<?php echo $singleStolen['reportID']; ?>">
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
			<p class="alignright">
				<a href="/index.php?action=reportList">» All Reports</a>
			</p>
		</article>

		<article class="newest-hints box clearfix">
			<h2>Newest Hints</h2>
			<ol>
			<?php
				if (is_array($this->hints) && !empty($this->hints))  {
					foreach ($this->hints as $hint) { ?>
						<li id="<?php echo $hint['hintID']; ?>">
							<a href="<?php echo WEB_BASE_URL ?>index.php?action=reportDetails&amp;reportID=<?php echo $hint['reportID']; ?>&amp;showHints=true">
							<?php echo readableDateTime($hint['hintWhen']) . '<br /> ' . $hint['hintWhat']; ?>
							</a>
						</li>
					<?php
					}
				}
				?>
			</ol>
			<p class="alignright">
				<a href="/index.php?action=hintList">» All Hints</a>
			</p>
		</article>
	</section>

	<section class="sidebar sidebar-second">
		<article class="stats box clearfix">
			<h3>Stats</h3>
			<table>
				<tr>
					<td>Bikes returned</td>
					<td>1</td>
				</tr>
				<tr>
					<td>Bikes stolen</td>
					<td>1</td>
				</tr>
				<tr>
					<td>Hints given</td>
					<td>1</td>
				</tr>
			</table>
			<p class="alignright">
				<a href="/index.php?action=statistics">» More</a>
			</p>
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
