$(function() {
  $('#select_sort').change(function() {
    if ($(this).val() == '') {
      return;
    }
    window.location = $(this).val();
  });
});

$(function () {
  $('[name=dev-view-sort]').change(function () {
    if ($(this).is(':checked')) {
      $('#dev-sort-item').slideDown(300);
    } else {
      $('#dev-sort-item').slideUp(300);
    }
  });
  return false;
});

$(function () {
  $('.dev-outbound-flag').change(function () {

    window.location.href = $("#hideOutboundUrl").val();

  });
  return false;
});
