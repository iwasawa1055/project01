var AppCreditCardEdit =
{
    a: function () {
        $('#execute').on('click', function (e) {
            gmoCreditCardPayment.setGMOTokenAndSubmit();
        });
    }
}

/*
 * document ready
 * */
$(function()
{
  AppCreditCardEdit.a();
});
