<?php
require_once('views/base.view.php');

class SearchView extends BaseView {

  private $results;

	function __construct($results) {
		$this->results = $results;
	}


	protected function doShow() { ?>

<div class="row-fluid">
	<div class="span12">
		<div class="wrapper">

			<div class="search-results box">
			<h3>Search results for »<?php echo $_GET['search']; ?>«</h3>
			<?php
				if (is_array($this->results) && !empty($this->results)) { ?>
				<ul>
					<?php foreach ($this->results as $reportID => $result) { ?>
					<li><a href="index.php?action=reportDetails&amp;reportID=<?php echo $reportID; ?>">
						<?php echo readableDateTime($result['noticedTheft']) . ' - ' . $result['city'] . ', ' . $result['bikeType'] . ' (' . $result['color'] . ')'; ?>
					</a></li>
				<?php } ?>
				</ul>
				<?php } else { ?>
				<p>
					Sorry, no search results found.
				</p>
				<?php } ?>
			</div>

			<script>
				var searchResults = <?php echo json_encode($this->results); ?>
			</script>
			<div class="bikemap bikemap-searchresults" data-bikemaptype="searchresults"></div>

		</div>
	</div>
</div>

<p>
	<a href="index.php?action=home">Return to the Homepage</a>
</p>

	<?php
	}
}

?>
