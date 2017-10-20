var AppAddCredit =
{
    a: function () {
        // GMO
        $('button').on('click', function (e) {
            gmoCreditCardPayment.setGMOTokenAndSubmit();
        });
    },
}

/*
 * document ready
 * */
$(function()
{
    AppAddCredit.a();
});
