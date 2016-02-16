$(function() {
  $('#select_sort').change(function() {
    if ($(this).val() == '') {
      return;
    }
    window.location = $(this).val();
  });
});
