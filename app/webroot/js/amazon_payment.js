var AppAmazonPaymentLogin =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    a: function () {
        window.onAmazonLoginReady = function(){
            amazon.Login.setClientId(AppAmazonPayment.ClientId);
        };
        window.onAmazonPaymentsReady = function() {
            // Render the button here.
            showButton();
        };
    }
}

/*
 * document ready
 * */
$(function()
{
    AppAmazonPaymentLogin.a();
});

