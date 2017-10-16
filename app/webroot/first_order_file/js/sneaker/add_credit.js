var AppAddCredit =
{
    a: function () {
        $('#execute').on('click', function (e) {
            $("#credit_info").gmoCreditPayment({
                'shopId': $('#shop_id').val()
            })
            .gmoCreditPayment('setGMOTokenAndSubmit')
        });
    }
}

/*
 * document ready
 * */
$(function(){
    AppAddCredit.a();
});
