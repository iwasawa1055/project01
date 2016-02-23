// animsition
$(function() {
  $('a[href^="/"]a[target!="_blank"]').addClass('animsition-link');
  $('button[type=submit]').addClass('page-transition-link');
  $('.animsition').animsition();

  $('.agree-before-submit[type="checkbox"]').click(checkAgreeBeforeSubmit);
  checkAgreeBeforeSubmit();

  $('form').submit(function() {
    $('button[type=submit]', this).attr('disabled', 'true');
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
