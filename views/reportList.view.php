<?php
require_once('views/base.view.php');
require_once('config/config.php');

class ReportListView extends BaseView {

	private $recentlyStolen;

	function __construct($recentlyStolen)  {
		$this->recentlyStolen = $recentlyStolen;
	}

	protected function doShow() { ?>
<h1>All Reports</h1>
<table class="table table-bordered table-striped table-sortable">
	<thead>
		<tr>
			<th>Reported on</th>
			<th>Noticed theft</th>
			<th>City</th>
			<th>Biketype</th>
			<th>Color</th>
			<th>State</th>
			<th></td>
		</tr>
	</thead>
	<tbody>
<?php
	if (is_array($this->recentlyStolen) && !empty($this->recentlyStolen))  {
		foreach ($this->recentlyStolen as $singleStolen) { ?>
			<tr>
			<td><?php echo readableDateTime($singleStolen['date']); ?></td>
			<td><?php echo readableDateTime($singleStolen['noticedTheft']); ?></td>
			<td><?php echo $singleStolen['postcode']; ?> <?php echo $singleStolen['city']; ?></td>
			<td><?php echo $singleStolen['bikeType']; ?></td>
			<td><?php echo $singleStolen['color']; ?></td>
			<td><?php echo $singleStolen['state']; ?></td>
			<td>
				<a href="<?php echo WEB_BASE_URL ?>index.php?action=reportDetails&amp;reportID=<?php echo $singleStolen['reportID']; ?>">Show reportdetails</a>
			</td>
			</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>

	<?php
	}
}

?>
