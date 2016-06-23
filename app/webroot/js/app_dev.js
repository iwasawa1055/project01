// animsition
$(function() {
  $('a[href^="/"]a[target!="_blank"]').addClass('animsition-link');
  $('button[type=submit]').addClass('page-transition-link');
  $('.animsition').animsition({
    inDuration: 500,
    outDuration: 100,
  });

  $('.agree-before-submit[type="checkbox"]').click(checkAgreeBeforeSubmit);
  checkAgreeBeforeSubmit();

  $('form').submit(function() {
    $('button[type=submit]', this).attr('disabled', 'true');
    if ($('button[type=submit]', this).hasClass('submit_after_restore')) {
      setTimeout(function() {
        $('button.submit_after_restore').attr('disabled', false);
      }, 1000);
    }
  });

  $('select.select-add-address').change(function() {
    if ($(this).val() == '-99') {
      $('form.select-add-address-form').submit();
    }
  });
});

function checkAgreeBeforeSubmit() {
  var count = $('.agree-before-submit[type="checkbox"]').length;
  if (0 < count) {
    $('#page-wrapper button[type=submit]').attr('disabled', 'true');
    if (count === $('.agree-before-submit[type="checkbox"]:checked').length) {
      $('#page-wrapper button[type=submit]').attr('disabled', null);
    }
  }
}
