$(document).ready(function() {

	// add the map to all map elements
	if ($('.bikemap').length) {
		$('.bikemap').bikemap({
		});
	}

	// print-button of report form
	$('.btn-print').on('click', function(e) {
		e.preventDefault();
		window.print();
	});


	// REPORT form stuff
	//================================================================================

	// datepicker for dateoftheft field
	// TODO pre-formatting date here (y-m-d) would be better?!
	if ($('#dateoftheft').length) {
		$('#dateoftheft').datepicker({
			firstDay: 1, // i dont like mondaaaaaaaays
			showAnim: 'fade',
			dateFormat: "dd.mm.yy",
		});
	}

	// timepicker for the start/end fields in the report-form
	$('#timeend, #timestart').timepicker({});

	// autocompletion for biketypes
	// TODO get this data from the rdf-store
	if ($('#biketype').length) {
		var availableTags = [
			"Mountainbike",
			"Rennrad",
			"Hollandrad",
			"Trekkingbike",
			"Citybomber"
		];

		$('#biketype').autocomplete({
			source: availableTags
		});
	}

	// tab navigation in report form
	$('#nav-report a').on('click', function(e) {
		e.preventDefault();
		$(this).tab('show');
	});

	// image upload duplication
	$('.btn-add-more-images').on('click', function(e) {
		e.preventDefault();
		duplicateUploadArea($(this));
	});

  // bike-parts duplication in report from
	$('.btn-add-more-parts').on('click', function(e) {
		e.preventDefault();
		duplicateParts($(this));
	});

	// summary-update for report form, requiredFieldChecker
	if ($('#view-report').length) {
		$('#nav-report a[href="#summary"]').on('click', function(e) {
			updateSummary();
			checkRequiredReportFields();
		});
	}



	//================================================================================
	//================================================================================
	//

	function checkRequiredReportFields() {
		$('#view-report input[required="required"]').each(function() {
			var $input = $(this);
			var trimmedVal = $.trim($input.val());
			var parentID = $input.parents('.tab-pane').attr('id');
			var $badge = $('#nav-report a[href="#' + parentID + '"]').find('.badge');
			if (trimmedVal.length === 0) { // if the field has no useful value
				$input.addClass('error');
				$badge.addClass('badge-important');
				$('#summary-' + $input.attr('id')).text('Please enter correct value').addClass('alert');
			} else {
				$input.removeClass('error');
				$badge.removeClass('badge-important').addClass('badge-success');
				$('#summary-' + $input.attr('id')).removeClass('alert');
			}
		});
	}

	function updateSummary() {
		$('#view-report input[type="text"], #view-report input[type="file"], #view-report textarea').each(function() {
			var $input = $(this);
			var summaryID = '#summary-' + $input.attr('id');

			if ($input.attr('type') === 'file') {
				$('#summary-images').html($('#summary-images').html() + $input.val() + '<br />');

			} else if ($input.attr('name') ==='comptype[]' || $input.attr('name') === 'compname[]') {
				if ($input.attr('name') === 'comptype[]') {
					var number = $input.parent().data('bikeparts-counter');
					$('#summary-components').html(
						/* TODO bug with colon */
						$('#summary-components').html() + $input.val() + ': ' + $('#compname-' + number).val() + '<br />'
					);
				}
			} else {
				$(summaryID).text($input.val());
			}
		});

	}

	function duplicateUploadArea($button) {
		var $uploadArea = $button.parent();
		var $newUploadArea = $uploadArea.clone();
		var counter = $uploadArea.data('upload-counter');

		$newUploadArea.insertAfter($uploadArea);
		// after inserting the new area remove the plus button
		$button.remove();
		counter += 1;

		$newUploadArea.attr('data-upload-counter', counter);
		$newUploadArea.find('label').attr('for', 'bikeimage-' + counter);
		$newUploadArea.find('input').attr('id', 'bikeimage-' + counter).val('');
		$newUploadArea.find('.counter').text(counter);
		$newUploadArea.find('button').on('click', function(e) {
			e.preventDefault();
			duplicateUploadArea($(this));
		});
	}

	function duplicateParts($button) {
		var $partsArea = $button.parent();
		var $newParts = $partsArea.clone();
		var counter = $partsArea.data('bikeparts-counter');

		$newParts.insertAfter($partsArea);
		// after inserting the new area remove the plus button
		$button.remove();
		counter += 1;

		$newParts.attr('data-bikeparts-counter', counter);
		$newParts.find('label[for^="comptype"]').attr('for', 'comptype-' + counter);
		$newParts.find('label[for^="compname"]').attr('for', 'compname-' + counter);
		$newParts.find('input[id^="comptype"]').attr('id', 'comptype-' + counter).val('');
		$newParts.find('input[id^="compname"]').attr('id', 'compname-' + counter).val('');
		$newParts.find('button').on('click', function(e) {
			e.preventDefault();
			duplicateParts($(this));
		});
	}


});
