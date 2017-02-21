var AppAddEmail =
{
    a: function () {

        $('.agree-before-submit[type="checkbox"]').click(checkAgreeBeforeSubmit);

        checkAgreeBeforeSubmit();

        // 利用規約チェック 複数で使用する場合first_order/app_devに移動
        function checkAgreeBeforeSubmit() {
            var count = $('.agree-before-submit[type="checkbox"]').length;
            if (0 < count) {
                $('#js-agreement_on_page button[type=submit]').attr('disabled', 'true');
                if (count === $('.agree-before-submit[type="checkbox"]:checked').length) {
                    $('#js-agreement_on_page button[type=submit]').attr('disabled', null);
                }
            }
        }
    },

}

/*
 * document ready
 * */
$(function()
{
    AppAddEmail.a();
});
