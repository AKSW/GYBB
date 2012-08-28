<?php
require_once('classes/autoloader.php');
require_once('config/config.php');
require_once('classes/utils.php');
require_once('templates/headerBase.php');
?>
<html>
<body>
<h2>Setup Graph </h2>
<?php 

if ($_SERVER['REQUEST_METHOD'] == "GET") {
//GET
?>

<p> If you click OK below, the graph will be deleted and setup. 
	This operation will destroy all submitted reports.</p>

<form method="POST">
	<input type="hidden" name="action" value="obey" />
	<input type="submit" value="OK" />
</form>

<p><strong>Remember:</strong> with great power comes great responsibility!</p>
<?php 

} else {
//POST:
	

?>

	<p>Performing operations...</p>
	<ol>
	
<?php

	ini_set("display_errors", "stdout");
	$qf = new QueryFactory();
	$qf->execSparql("DROP  SILENT  GRAPH <" . $qf->graphUri() . ">" );
?>
		<li>Dropped Graph.. </li>

<?php
	$qf->execSparql("CREATE  SILENT  GRAPH <" . $qf->graphUri() . ">" );
?>
		<li>Created Graph.. </li>


<?php
	$fh = fopen('ontology.owl', "r");
	$ttl = fread($fh, 512 * 1024);//max 500K initial owl
	fclose($fh);
?>
		<li>Read owl.. </li>

<?php
		$qf->ttl($ttl);
?>
		<li>Saved ontology..</li>
		<li>Setup done!</li>
	</ol>
<?php 
	}
?>

</body>
</html>