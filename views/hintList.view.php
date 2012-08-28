<?php
require_once('views/base.view.php');

class HintListView extends BaseView {

	private $hints;

	function __construct($hints)  {
		$this->hints = $hints;
	}

	protected function doShow() { ?>
<h1>All Hints</h1>
<table class="table table-bordered table-striped table-sortable">
	<thead>
		<tr>
			<th>Hint given on</th>
			<th>Hint-date</th>
			<th>Hint</th>
			<th>by</th>
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
			<td><?php echo $hint['hintUser']; ?></td>
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

	<?php
	}
}

?>
