var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonOrderReferenceId: '',
    buyerBillingAgreementConsentStatus: false,

    a: function () {
        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPayWallet.ClientId);

            // アドレスWidgetを表示
            new OffAmazonPayments.Widgets.AddressBook({
                sellerId: AppAmazonPayWallet.SELLER_ID,

                onOrderReferenceCreate: function(orderReference) {
                    // Here is where you can grab the Order Reference ID.
                    AppAmazonPayWallet.AmazonOrderReferenceId = orderReference.getAmazonOrderReferenceId();
                },
                // Widgets起動状態
                onReady: function(billingAgreement) {

                    // カード選択 Widgetを表示
                    new OffAmazonPayments.Widgets.Wallet({
                        sellerId: AppAmazonPayWallet.SELLER_ID,
                        design: {
                            designMode: 'responsive'
                        },
                        onReady: function() {
                            console.log("OffAmazonPayments.Widgets.Wallet");
                        },
                        // カード選択変更時
                        onPaymentSelect: function () {
                        },
                        onError: function (error) {
                            console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                        }
                    }).bind("walletWidgetDiv");

                },
                // 住所選択変更時
                onAddressSelect: function () {
                    // do stuff here like recalculate tax and/or shipping
                    // お届希望日を取得
                    // AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonBillingAgreementId);

                },
                design: {
                    designMode: 'responsive'
                },
                onError: function (error) {
                    if(error.getErrorCode() == 'BuyerSessionExpired') {
                        amazon.Login.logout();
                        location.href = '/login/logout';
                    }
                }
            }).bind("addressBookWidgetDiv");
        };
    }
}

var AppEditAmazonPay =
{
    a: function () {
        $('#amazonPayLogout').on('click', function (e) {
            amazon.Login.logout();
            location.href = '/login/logout';
        });
    }
}
/*
 * document ready
 * */
$(function()
{
  AppAmazonPayWallet.a();
  AppEditAmazonPay.a();
});
