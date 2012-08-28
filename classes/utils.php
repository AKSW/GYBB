<?php
require_once('classes/labels.php');
require_once('classes/sparqlConstants.php');


function inputHelper($id, $wrap = '', $placeholder = '', $required = false, $type = 'text', $class = '', $value = '', $label = '') {
	$lbl = new Labels();
	$labels = $lbl->getLabels();
	$output = '';

	$label = (empty($label)) ? $labels[$id] : $label;

	switch ($type) {
		case 'text':
			$output = ''
				. '<label for="' . $id . '">' . $label . '</label>' . "\n"
				. '<input type="text" id="' . $id . '" name="' . $id
				. '" value="' . $value . '" placeholder="' . $placeholder . '"';
			if (!empty($class)) $output .= ' class="' . $class . '"';
			if ($required) $output .= ' required="required"';
			$output .= '/>' . "\n";
			break;
		case 'textarea':
			$output = ''
				. '<label for="' . $id . '">' . $label . '</label>' . "\n"
				. '<textarea rows="5" cols="50" id="' . $id . '" name="' . $id . '" placeholder="'
				. $placeholder . '"';
			if (!empty($class)) $output .= ' class="' . $class . '"';
		  if ($required) $output .= 'required="required"';
			$output .= '>' . $value . '</textarea>' . "\n";
			break;

		default: /* a simple button */
			$output = '<button class="btn btn-' . $id . '">' . $label . '</button>';
			break;
	}

	if (!empty($wrap)) {
    $output = '<' . $wrap . '>' . $output . '</' . $wrap . '>';
	}

	echo $output;
}


function summaryHelper($id, $label = '') {
	$lbl = new Labels();
	$labels = $lbl->getLabels();
	$label = (empty($label)) ? $labels[$id] : $label;

	echo '<tr><td>' . $label . '</td><td id="summary-' . $id . '"></td>';
}


function reportDetailsHelper($id, $label = '', $value = '') {
	$lbl = new Labels();
	$labels = $lbl->getLabels();

	if (empty($label) && isset($labels[$id])) {
			$label = $labels[$id];
	} else {
		$label = $id;
	}

	echo '<tr><td>' . $label . '</td><td>' . $value . '</td>';
}


function cleanupSparqlResults($results)  {
	$finalResults = array();
	$constants = new SparqlConstants();

	foreach ($results as $singleResult) {
		$singleTempArray = array();
		foreach ($singleResult as $key => $results) {

			// cycle through all uris that may be stripped from the value
			foreach ($constants->allPrefixes as $uri) {
				if (strpos($results->value, $uri) !== false) {
					$value = str_replace($uri, '', $results->value);
					$singleTempArray[$key] = $value;
				}
			}

			// if there was no uri to strip, use the normal value
			if (!isset($singleTempArray[$key])) {
				$singleTempArray[$key] = $results->value;
			}
		}

		$finalResults[] = $singleTempArray;
	}

	return $finalResults;
}


function readableDateTime($dateTime)  {
	$dateTimeArray = explode('T', $dateTime);
	$dates = explode('-', $dateTimeArray[0]);
	$time = substr($dateTimeArray[1], 0, -4);

	return $dates[2] . '.' . $dates[1] . '.' . $dates[0] . ' ' . $time;
}


function exportListLink($name, $format) {
	$link = '<li><a href="index.php?action=export&amp;format=' . $format;

	$id = false;
	if (isset($_POST['reportID'])) $id = $_POST['reportID'];
	if (isset($_GET['reportID'])) $id = $_GET['reportID'];

	if ($id !== false) {
		$link .= '&amp;reportID=' . $id;
	}
	$link .= '">' . $name . '</a></li>';

	echo $link;
}


?>
