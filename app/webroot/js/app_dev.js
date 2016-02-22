// animsition
$(function() {
    $('a[href^="/"]a[target!="_blank"]').addClass('animsition-link');
    $('.animsition').animsition();

    $('.agree-before-submit[type="checkbox"]').click(checkAgreeBeforeSubmit);
    checkAgreeBeforeSubmit();
});

function checkAgreeBeforeSubmit() {
  var count = $('.agree-before-submit[type="checkbox"]').length;
  if (0 < count) {
    $('button[type=submit]').attr('disabled', 'true');
    if (count === $('.agree-before-submit[type="checkbox"]:checked').length) {
      $('button[type=submit]').attr('disabled', null);
    }
  }
}
