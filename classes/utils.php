<?php
require_once('classes/labels.php');


function inputHelper($id, $wrap = '', $placeholder = '', $required = false, $type = 'text',  $value = '', $label = '') {
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
			if ($required) $output .= 'required="required"';
			$output .= '/>' . "\n";
			break;
		case 'textarea':
			$output = ''
				. '<label for="' . $id . '">' . $label . '</label>' . "\n"
				. '<textarea rows="5" cols="50" id="' . $id . '" name="' . $id . '" placeholder="'
				. $placeholder . '"';
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


function reportDetailsHelper($id, $value = '', $label = '') {
	$lbl = new Labels();
	$labels = $lbl->getLabels();

	if (empty($label) && isset($labels[$id])) {
			$label = $labels[$id];
	} else {
		$label = $id;
	}

	// TODO maybe we wanna show these values?
	echo '<tr><td>' . $label . '</td><td>' . $value . '</td>';

}

?>
