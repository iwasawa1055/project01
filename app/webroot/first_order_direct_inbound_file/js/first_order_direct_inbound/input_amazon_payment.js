var AppAmazonPayment =
{
/*
    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            $('form').submit();
        });
    },
*/
    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            /*
            var self = $(this);
            var add_reference  = $('<input type="hidden" name="order_reference_id">');
            add_reference.val(AppAmazonPaymentWallet.orderReferenceId);
            add_reference.insertAfter(self);

            var add_billing  = $('<input type="hidden" name="amazon_billing_agreement_id">');
            add_billing.val(AppAmazonPaymentWallet.AmazonBillingAgreementId);
            add_billing.insertAfter(self);
            */
            console.log("test"); 

            $(this).closest("form").submit();
        });
    },
}


var AppAmazonPaymentWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonBillingAgreementId: '',

    a: function () {
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPaymentWallet.ClientId);

            new OffAmazonPayments.Widgets.AddressBook({
                sellerId: AppAmazonPaymentWallet.SELLER_ID,
                onOrderReferenceCreate: function (orderReference) {
                    orderReferenceId = orderReference.getAmazonOrderReferenceId();
                    AppAmazonPaymentWallet.AmazonBillingAgreementId = orderReference.getAmazonOrderReferenceId();
                    console.log(orderReferenceId);
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

            new OffAmazonPayments.Widgets.Consent({
                sellerId: AppAmazonPaymentWallet.SELLER_ID,
                amazonBillingAgreementId: AppAmazonPaymentWallet.AmazonBillingAgreementId,
                // amazonBillingAgreementId obtained from the Amazon Address Book widget. amazonBillingAgreementId: amazonBillingAgreementId,
                design: {
                    designMode: 'responsive'
                },

                onReady: function(billingAgreementConsentStatus){
                    // Called after widget renders buyerBillingAgreementConsentStatus =
//                    billingAgreementConsentStatus.getConsentStatus(); // getConsentStatus returns true or false
                    // true – checkbox is selected
                },// false – checkbox is unselected - default
                onConsent: function(billingAgreementConsentStatus) {
                    buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus();
                    // getConsentStatus returns true or false
                    // true – checkbox is selected – buyer has consented
                    // false – checkbox is unselected – buyer has not consented
                    // Replace this code with the action that you want to perform
                    // after the consent checkbox is selected/unselected.
                },
                onError: function(error) {

                }// your error handling code
            }).bind("consentWidgetDiv ");
        };
    },
    b: function () {

    }
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
