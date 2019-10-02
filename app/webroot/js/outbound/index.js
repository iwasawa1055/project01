var AppIndex =
{
    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            $("#target_form").submit();
        });
    }
}

/*
 * document ready
 * */
$(function()
{
  AppIndex.a();
});
