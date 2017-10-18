var AppAddCredit =
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
$(function(){
    AppAddCredit.a();
});
