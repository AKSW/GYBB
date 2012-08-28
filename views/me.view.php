<?php
require_once('views/base.view.php');
require_once('config/config.php');

class MeView extends BaseView {

	private $reports;
	private $hints;

	function __construct($reports, $hints)  {
		$this->reports = $reports;
		$this->hints = $hints;
	}

	protected function doShow() { ?>
<h1>My Reports</h1>
<?php if (empty($this->reports)) : ?>
<p>You have no reports yet.</p>
<?php else : ?>
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
	if (is_array($this->reports) && !empty($this->reports))  {
		foreach ($this->reports as $report) { ?>
			<tr>
			<td><?php echo readableDateTime($report['date']); ?></td>
			<td><?php echo readableDateTime($report['noticedTheft']); ?></td>
			<td><?php echo $report['postcode']; ?> <?php echo $report['city']; ?></td>
			<td><?php echo $report['bikeType']; ?></td>
			<td><?php echo $report['color']; ?></td>
			<td><?php echo $report['state']; ?></td>
			<td>
				<a href="<?php echo WEB_BASE_URL ?>index.php?action=reportDetails&amp;reportID=<?php echo $report['reportID']; ?>">Show reportdetails</a>
			</td>
			</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>
<?php endif; ?>

<h1>My Hints</h1>
<?php if (empty($this->hints)) : ?>
<p>
	You have no hints yet.
</p>
<?php else : ?>
<table class="table table-bordered table-striped table-sortable">
	<thead>
		<tr>
			<th>Hint given on</th>
			<th>Hint-date</th>
			<th>Hint</th>
			<th>for Report</td>
		</tr>
	</thead>
	<tbody>
<?php
	if (is_array($this->hints) && !empty($this->hints))  {
		foreach ($this->hints as $hint) { ?>
			<tr>
			<td><?php echo readableDateTime($hint['created']); ?></td>
			<td><?php echo readableDateTime($hint['hintWhen']); ?></td>
			<td><?php echo $hint['hintWhat']; ?></td>
			<td>
				<a href="<?php echo WEB_BASE_URL ?>index.php?action=reportDetails&amp;reportID=<?php echo $hint['reportID']; ?>">Show reportdetails</a>
			</td>
			</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>

<?php endif; ?>

	<?php
	}
}

?>
