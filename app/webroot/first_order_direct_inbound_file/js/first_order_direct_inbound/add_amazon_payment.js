var AppAmazonPayment =
{
    DELIVERY_ID_PICKUP : '6',
    DELIVERY_ID_MANUAL : '7',
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
    b: function(){

        var elem_day = $('#InboundDayCd');
        var elem_time = $('#InboundTimeCd');

        if(elem_day.val() === null) {
            $('option:first', elem_day).prop('selected', true);
            elem_day.attr("disabled", "disabled");
            elem_day.empty();
            $('option:first', elem_time).prop('selected', true);
            elem_time.attr("disabled", "disabled");
            elem_time.empty();

            $.post('/FirstOrderDirectInbound/as_getInboundDatetime', {
                    Inbound: {delivery_carrier: '6_1'}
                },
                function (data) {
                    var pNotFound = '<p class="error-message search-address-error-message">集荷時間取得エラー。</p>';

                    if (data.result.date) {

                        var optionItems = new Array();
                        if (data.status) {
                            $.each(data.result.date, function () {
                                optionItems.push(new Option(this.text, this.date_cd));
                            });
                            elem_day.append(optionItems);

                            $('#select_delivery_day').val(JSON.stringify(data.result.date));
                        } else {
                            elem_day.after(pNotFound);
                        }
                    }
                    ;
                    if (data.result.time) {
                        var optionItems = new Array();
                        if (data.status) {
                            $.each(data.result.time, function () {
                                optionItems.push(new Option(this.text, this.time_cd));
                            });
                            elem_time.append(optionItems);

                            $('#select_delivery_time').val(JSON.stringify(data.result.time));
                        } else {
                            // dayで表示済
                            //elem_day.after(pNotFound);
                        }
                    }
                    ;
                },
                'json'
            ).always(function () {
                elem_day.removeAttr("disabled");
                elem_time.removeAttr("disabled");
            });
        }
    },
    c: function () {
        // 預け入れ方法の選択初期化
        if($("#yamato").prop('checked')) {
            $('.dsn-arrival').hide('fast');
            $('.dsn-yamato').show('fast');
        } else {
            $('.dsn-arrival').show('fast');
            $('.dsn-yamato').hide('fast');
        }
    }
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
    AppAmazonPayment.b();
    AppAmazonPayment.c();
    AppAmazonPaymentWallet.a();
//    AppAmazonPaymentWallet.b();
});