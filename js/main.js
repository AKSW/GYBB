$(document).ready(function() {

	// add the map to all map elements
	if ($('.bikemap').length) {
		var $bikemaps = $('.bikemap');
		$bikemaps.each(function() {
			var lon, lat, reportID;
			var options = {};
			var mapType = $(this).data('bikemaptype');

			// set some options for the map depending on type
			if (mapType === 'exploration') {
				options = {
					zoom: 4
				};

			} else if (mapType === 'searchresults') {
				options = {
					zoom: 4
				};

			} else if (mapType === 'report') {
				options = {
					zoom: 15
				};

			} else if (mapType === 'report-details') {
				options = {
					zoom: 15,
					startLon: $(this).data('bikemaplon'),
					startLat: $(this).data('bikemaplat')
				};

			} else if (mapType === 'add-hint') {
				options = {
					zoom: 15,
					startLon: $(this).data('bikemaplon'),
					startLat: $(this).data('bikemaplat')
				};

			} else if (mapType === 'hints') {
				options = {
					zoom: 15,
					report: $(this).data('bikemapreportid'),
					startLon: $(this).data('bikemaplon'),
					startLat: $(this).data('bikemaplat')
				};
			}
      // create the map with the options above
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
			maxHeight: '90%'
		});
	}

	$('.table-sortable').tablesorter();


	// load images for recently stolen bikes
	if ($('.stolen-bike').length)  {
		$('.stolen-bike').on('mouseenter', function(e)  {
			$(this).find('.stolen-image').removeClass('hidden');
		});
		$('.stolen-bike').on('mouseleave', function(e)  {
			$(this).find('.stolen-image').addClass('hidden');
		});
	}

	if ($('#hint-form').length) {
		$('#hint-form').hide();

		$('.btn-hint').on('click', function(e) {
			e.preventDefault();
			$.colorbox({
				inline: true,
				href: '#hint-form',
				width: 850,
				height: 580,
				onComplete: function() {
					$('#colorbox #hint-form').show();
				},
				onClosed: function() {
					$('#hint-form').hide();
				}
			});

		});

	}


	// tab navigation in report form
	$('#nav-report a').on('click', function(e) {
		e.preventDefault();
		$(this).tab('show');
	});


	// REPORT form stuff
	//================================================================================

	// timepicker for the start/end fields in the report-form
	$('#lastSeen, #noticedTheft, #hintWhen').datetimepicker({
		firstDay: 1, // i dont like mondaaaaaaaays
		showAnim: 'fade',
		maxDate: '0d',
		dateFormat: "dd.mm.yy",
		timeFormat: "hh:mm",
		stepMinute: 5
	});

	// autocompletion for some fields
	if ($('.suggestion').length) {
		$('.suggestion').each(function()  {
			var id = $(this).attr('id');
			// just take the firstpart before the dash
			var splitted = id.split('-');
			var type = splitted[0];

			$(this).autocomplete({
				source: 'index.php?action=suggestion&type=' + type
			});
		});
	}


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
