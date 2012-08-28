$(document).ready(function() {

	// add the map to all map elements
	if ($('.bikemap').length) {
		var $bikemaps = $('.bikemap');
		$bikemaps.each(function() {
			var options = {};
			var mapType = $(this).data('bikemaptype');

			// set some options for the map depending on type
			if (mapType === 'exploration') {
				options = { zoom: 13 }
			} else if (mapType === 'report') {
				options = { zoom: 15 }
			}

			$(this).bikemap(options);
		});
	}

	// print-button of report form
	$('.btn-print').on('click', function(e) {
		e.preventDefault();
		window.print();
	});

	// Add Colorbox to every lightbox-link with click-enlarge
	if ($('a.lightbox').length) {
		$('a.lightbox').colorbox({
			opacity: 0.8,
			loop: false,
			maxWidth: '90%',
			maxHeight: '90%',
		});
	}

	// REPORT form stuff
	//================================================================================

	// datepicker for dateoftheft field
	if ($('#dateOfTheft').length) {
		$('#dateOfTheft').datepicker({
			firstDay: 1, // i dont like mondaaaaaaaays
			showAnim: 'fade',
			maxDate: '0d',
			dateFormat: "dd.mm.yy",
		});
	}

	// timepicker for the start/end fields in the report-form
	$('#lastSeen, #noticedTheft').timepicker({});

	// autocompletion for biketypes
	// TODO get this data from the rdf-store
	if ($('#bikeType').length) {
		var availableTags = [
			"Mountainbike",
			"Rennrad",
			"Hollandrad",
			"Trekkingbike",
			"Citybomber"
		];

		$('#bikeType').autocomplete({
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

	if ($('.btn-home-boxes').length) {
		$('.btn-home-boxes').on('click', function(e) {
			e.preventDefault();
			$('.sidebar .box').toggleClass('hidden');
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
				$('#summary-' + $input.attr('id')).text('Please enter a correct value').addClass('alert');
			} else {
				$input.removeClass('error');
				$badge.removeClass('badge-important').addClass('badge-success');
				$('#summary-' + $input.attr('id')).removeClass('alert');
			}
		});
	}

	function updateSummary() {
		$('.summary-components-container').html('');
    var bikepartsHTML = '';
		var imagesHTML = '';

		$('#view-report input[type="text"], #view-report input[type="file"], #view-report textarea').each(function() {
			var $input = $(this);
			var summaryID = '#summary-' + $input.attr('id');

			if ($input.attr('type') === 'file') {
				if ($input.val().length) {
					imagesHTML += $input.val() + ': ' + $input.val() + '<br />';
				}

			} else if ($input.attr('name') ==='comptype[]' || $input.attr('name') === 'compname[]') {

				if ($input.attr('name') === 'comptype[]') {
					var number = $input.parent().data('bikeparts-counter');
					if ($input.val().length) {
						bikepartsHTML += $input.val() + ': ' + $('#compname-' + number).val() + '<br />';
					}
				}
			} else {
				$(summaryID).text($input.val());
			}
		});

		$('.summary-components-container').html(bikepartsHTML);
		$('.summary-images-container').html(imagesHTML);

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
		$newParts.find('input[id^="comptype"]').attr('id', 'comptype-' + counter).val('').focus();
		$newParts.find('input[id^="compname"]').attr('id', 'compname-' + counter).val('');
		$newParts.find('button').on('click', function(e) {
			e.preventDefault();
			duplicateParts($(this));
		});
	}


});
