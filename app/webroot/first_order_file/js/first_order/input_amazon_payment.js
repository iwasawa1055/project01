var AppAmazonPayment =
{

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            var self = $(this);
            var add  = $('<input type="hidden" name="order_reference_id">');
            add.val(AppAmazonPaymentWallet.orderReferenceId);
            add.insertAfter(self);
            $(this).closest("form").submit();
        });
    },
}


var AppAmazonPaymentWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonBillingAgreementId: '',
    orderReferenceId: '',

    a: function () {
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPaymentWallet.ClientId);

            new OffAmazonPayments.Widgets.AddressBook({
                sellerId: AppAmazonPaymentWallet.SELLER_ID,
                agreementType: 'BillingAgreement',
                onReady: function(billingAgreement) {
                    AppAmazonPaymentWallet.AmazonBillingAgreementId = billingAgreement.getAmazonBillingAgreementId();
                    AppAmazonPaymentWallet.orderReferenceId = billingAgreement.getAmazonBillingAgreementId();

                    console.log(AppAmazonPaymentWallet.AmazonBillingAgreementId);

                    new OffAmazonPayments.Widgets.Consent({
                        sellerId: AppAmazonPaymentWallet.SELLER_ID,
                        amazonBillingAgreementId: AppAmazonPaymentWallet.AmazonBillingAgreementId,
                        // amazonBillingAgreementId obtained from the Amazon Address Book widget. amazonBillingAgreementId: amazonBillingAgreementId,
                        design: {
                            designMode: 'responsive'
                        },
                        onReady: function(billingAgreementConsentStatus){
                            // Called after widget renders
                            //buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus(); // getConsentStatus returns true or false
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
                            console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                        }// your error handling code
                    }).bind("consentWidgetDiv ");
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
    }
}

/*
 * document ready
 * */
$(function()
{
    AppAmazonPayment.a();
    AppAmazonPaymentWallet.a();
});
