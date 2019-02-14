var AppCustomerRegisterAdd =
{
    a: function () {
        //** Auto Kana
        $('input.lastname').airAutoKana(
        {
            dest: 'input.lastname_kana',
            katakana: true
        });

        $('input.firstname').airAutoKana(
        {
            dest: 'input.firstname_kana',
            katakana: true
        });
    },
    b: function () {
        //ボタンの色を薄くする
        $('#execute').css("opacity", "0.5");

        $('#terms').on('click', function(){
            if ($('#terms').prop('checked')) {
                $('#execute').css("opacity", "1");

                if ($('#terms-error').length) {
                    $('#terms-error').remove();
                }
            } else {
                $('#execute').css("opacity", "0.5");
            }
        });

        $('#execute').on('click', function(){
            if ($('#terms').prop('checked')) {
                $('#CustomerRegistInfoCustomerAddAddressEmailForm').submit();
            } else {
                if ($('#terms-error').length == 0) {
                    $('#terms').parent('label').parent('li').append('<p class="valid-il" id="terms-error">個人情報について、利用規約をご確認下さい</p>');
                }
            }
        });
    },
};

/*
 * document ready
 * */
$(function()
{
    AppCustomerRegisterAdd.a();
    AppCustomerRegisterAdd.b();
});
