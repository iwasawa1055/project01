var AppEditAmazonPay =
{
    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonBillingAgreementId: '',
    buyerBillingAgreementConsentStatus: false,
    ErrorMessage: '一時的に Amazon Pay がご利用いただけません。「戻る」ボタンより前の画面へ戻り、再度お試しください。何度もこのメッセージが表示される場合はクレジットカードでご購入ください。',
    AmazonWidgetReadyFlag: false,
    a: function () {
        $('#amazonPayLogout').on('click', function (e) {
            amazon.Login.logout();
            location.href = '/login/logout';
        });
    },
    b: function () {
        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppEditAmazonPay.ClientId);
            AppEditAmazonPay.AmazonBillingAgreementId = $("#amazon_billing_agreement_id").val();
            AppEditAmazonPay.c();
        };
    },
    c: function () {
        // アドレスWidgetを表示
        new OffAmazonPayments.Widgets.AddressBook({
            sellerId: AppEditAmazonPay.SELLER_ID,
            agreementType: 'BillingAgreement',
            amazonBillingAgreementId: AppEditAmazonPay.AmazonBillingAgreementId,

            // Widgets起動状態
            onReady: function(billingAgreement) {
                AppEditAmazonPay.AmazonWidgetReadyFlag = true;
                if(AppEditAmazonPay.AmazonBillingAgreementId === '') {
                    AppEditAmazonPay.AmazonBillingAgreementId = billingAgreement.getAmazonBillingAgreementId();
                    $("#amazon_billing_agreement_id").val(AppEditAmazonPay.AmazonBillingAgreementId);
                }

                // カード選択 Widgetを表示
                new OffAmazonPayments.Widgets.Wallet({
                    sellerId: AppEditAmazonPay.SELLER_ID,
                    amazonBillingAgreementId: AppEditAmazonPay.AmazonBillingAgreementId,
                    design: {
                        designMode: 'responsive'
                    },
                    onReady: function() {
                        //　初回のみ定期購入チェックのウィジェットを表示
                        if($('#regist_user_flg').val() == '1') {
                            // 定期購入チェックを確認
                            new OffAmazonPayments.Widgets.Consent({
                                sellerId: AppEditAmazonPay.SELLER_ID,
                                amazonBillingAgreementId: AppEditAmazonPay.AmazonBillingAgreementId,
                                design: {
                                    designMode: 'responsive'
                                },
                                onReady: function(billingAgreementConsentStatus){
                                    if(typeof billingAgreementConsentStatus.getConsentStatus == 'function') {
                                        AppEditAmazonPay.buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus(); // getConsentStatus returns true or false
                                    }
                                },
                                onConsent: function(billingAgreementConsentStatus) {
                                    AppEditAmazonPay.buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus();
                                },
                                onError: function(error) {
                                    console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                                }
                            }).bind("consentWidgetDiv ");
                        }
                    },
                    // カード選択変更時
                    onPaymentSelect: function () {
                    },
                    onError: function (error) {
                        AppEditAmazonPay.c();
                    }
                }).bind("walletWidgetDiv");

            },
            // 住所選択変更時
            onAddressSelect: function () {
            },
            design: {
                designMode: 'responsive'
            },
            onError: function (error) {
                AppEditAmazonPay.c();
            }
        }).bind("addressBookWidgetDiv");
    },
    d: function () {
        $('#amazonPayComplete').on('click', function (e) {
            if(AppEditAmazonPay.buyerBillingAgreementConsentStatus != 'false') {
                document.form.submit();
                return;
            }
            alert('Amazon Pay をお支払方法に設定する 同意は必須です。');
        });
    },
}

/*
 * document ready
 * */
$(function()
{
    AppEditAmazonPay.a();
    AppEditAmazonPay.b();
    AppEditAmazonPay.d();
});
