var AppAmazonPayment =
{

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            $('form').submit();
        });
    },
}


var AppAmazonPaymentWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',

    a: function () {
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPaymentWallet.ClientId);

            new OffAmazonPayments.Widgets.AddressBook({
                sellerId: AppAmazonPaymentWallet.SELLER_ID,
                onOrderReferenceCreate: function (orderReference) {
                    orderReferenceId = orderReference.getAmazonOrderReferenceId();
                },
                onAddressSelect: function () {
                    // do stuff here like recalculate tax and/or shipping
                },
                design: {
                    designMode: 'responsive'
                },
                onError: function (error) {
                    console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                }
            }).bind("addressBookWidgetDiv");

            new OffAmazonPayments.Widgets.Wallet({
                sellerId: AppAmazonPaymentWallet.SELLER_ID,
                onPaymentSelect: function () {
                },
                design: {
                    designMode: 'responsive'
                },
                onError: function (error) {
                    console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                }
            }).bind("walletWidgetDiv");
        };
    },
}

/*
 * document ready
 * */
$(function()
{
    AppAmazonPayment.a();
    AppAmazonPaymentWallet.a();
//    AppAmazonPaymentWallet.b();
});
