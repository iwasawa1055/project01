var AppAmazonPaymentWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',

    a: function () {
        window.onAmazonLoginReady = function() {
            // amazon.Login.setClientId(AppAmazonPaymentWallet.ClientId);
        };
    },
    b: function () {

        $(window).load(function () {
            new OffAmazonPayments.Widgets.AddressBook({
                    sellerId: AppAmazonPaymentWallet.SELLER_ID,
                    agreementType: 'BillingAgreement',
                onReady: function(billingAgreement) {
                    var billingAgreementId = billingAgreement.getAmazonBillingAgreementId();
                },
                onAddressSelect: function(billingAgreement) {
                },
                design: {
                    designMode: 'responsive'
                },
                onError: function(error) {
                } // your error handling code

                }).bind("addressBookWidgetDiv");
        });

    },
    c: function () {

        $(window).load(function () {
            new OffAmazonPayments.Widgets.Wallet({
                sellerId: AppAmazonPaymentWallet.SELLER_ID,
                // amazonBillingAgreementId obtained from the AddressBook widget amazonBillingAgreementId: amazonBillingAgreementId, onPaymentSelect: function(billingAgreement) {
                // Replace this code with the action that you want to perform },// after the payment method is selected.
                design: {
                    designMode: 'responsive'
                },
                onError: function(error) {
                    console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                } // your error handling code
            }).bind("walletWidgetDiv");
        });
    }
}

/*
 * document ready
 * */
$(function()
{
    AppAmazonPaymentWallet.a();
    AppAmazonPaymentWallet.b();
    AppAmazonPaymentWallet.c();
});
