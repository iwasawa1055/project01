$(function() {
  $('#address_select').change(function() {
    var id = $(this).val();
    $('.address_id').val(id);
    checkId();
  });
  checkId();
});

function checkId () {
  $('.address_id').each(function() {
    var id = $(this).val();
    if (0 < id.length && isFinite(id)) {
      $('button', $(this).parent()).attr('disabled', null);
    } else {
      $('button', $(this).parent()).attr('disabled', 'true');
    }
  });
}
