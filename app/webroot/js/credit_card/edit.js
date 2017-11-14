var AppCreditCardEdit =
{
    a: function () {
        $('#execute').on('click', function (e) {
            if($('#registerd_credit_card').val() == '1') {
                gmoCreditCardPayment.setGMOTokenAndUpdateCreditCardAndSubmit();
            } else {
                gmoCreditCardPayment.setGMOTokenAndRegisterCreditCardAndSubmit();
            }
        });
    }
};

/*
 * document ready
 * */
$(function()
{
    AppCreditCardEdit.a();
});
