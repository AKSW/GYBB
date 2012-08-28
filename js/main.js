$(document).ready(function() {

  // add the map to all map elements
  if ($('.bikemap').length) {
    $('.bikemap').bikemap();
  }

  if ($('#dateoftheft').length) {
    $('#dateoftheft').datepicker({
      firstDay: 1, // i dont like mondaaaaaaaays
      showAnim: 'fade',
      dateFormat: "dd.mm.yy",
      dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
      monthNames: ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"],
      monthNamesShort: ["Jan", "Feb", "Mrz", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
      nextText: "später",
      prevText: "früher"
    });
  }

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

  $('#nav-report a').on('click', function(e) {
    e.preventDefault();
    $(this).tab('show');
  });


  // TODO quick and dirty image-upload duplication
  $('.btn-add-more-images').on('click', function(e) {
    e.preventDefault();
    duplicateUploadArea($(this));
  });


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
    $newUploadArea.find('input').attr('id', 'bikeimage-' + counter);
    $newUploadArea.find('.counter').text(counter);
    $newUploadArea.find('button').on('click', function(e) {
      e.preventDefault();
      duplicateUploadArea($(this));
    });
  }

});
