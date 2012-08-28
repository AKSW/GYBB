<?php
require_once('classes/autoloader.php');
require_once('config/config.php');
require_once('classes/utils.php');
require_once('templates/headerBase.php');
?>

<html>
<body>
<h2>Setup Graph</h2>

<?php if ($_SERVER['REQUEST_METHOD'] == "GET") : ?>

<p>
	If you click OK below, the graph will be deleted and setup.
	This operation will destroy all submitted reports.
</p>

<form method="POST">
	<input type="hidden" name="action" value="obey" />
	<input type="submit" value="OK" />
</form>

<p><strong>Remember:</strong> with great power comes great responsibility!</p>

<?php else : // not get ?>

<p>Performing operations...</p>
<ol>

<?php
	ini_set("display_errors", "stdout");
	$qf = new QueryFactory();
	$qf->execSparql("DROP  SILENT  GRAPH <" . RESOURCE_GRAPH . ">" );
	$qf->execSparql("DROP  SILENT  GRAPH <" . ONTOLOGY_GRAPH . ">" );
	$qf->execSparql("DROP  SILENT  GRAPH <" . DATACUBE_GRAPH . ">" );
	$qf->execSparql("DROP  SILENT  GRAPH <" . VOID_GRAPH . ">" );
?>
	<li>Dropped Graphs... </li>

<?php
	$qf->execSparql("CREATE  SILENT  GRAPH <" . ONTOLOGY_GRAPH . ">" );
?>
	<li>Created Ontology-Graph... </li>

<?php
  // reading the ontology
	$fh = fopen('ontology.owl', "r");
	$ontology = fread($fh, 512 * 1024);//max 500K initial owl
	fclose($fh);
?>
	<li>Read owl.. </li>

<?php
	$qf->ttl($ontology);
?>
	<li>Saved ontology..</li>

<?php
	// reading the void ontoloy
	$fh = fopen('void.owl', "r");
	$void = fread($fh, 512 * 1024); //max 500K initial owl
	fclose($fh);
?>
	<li>Read void owl.. </li>

<?php
	$qf->ttl($void, VOID_GRAPH);
?>
	<li>Saved void ontology..</li>

	<li>Setup done!</li>
</ol>


<?php endif; ?>

</body>
</html>
