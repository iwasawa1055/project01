
var AppAmazonPay =
{
    DELIVERY_ID_PICKUP : '6',
    DELIVERY_ID_MANUAL : '7',

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            var self = $(this);
            var add_billing  = $('<input type="hidden" name="amazon_order_reference_id">');
            add_billing.val(AppAmazonPayWallet.AmazonOrderReferenceId);
            add_billing.insertAfter(self);

            // サブミット前チェック確認
            // 定期購入未チェックでエラー
            if(AppAmazonPayWallet.buyerBillingAgreementConsentStatus == 'false') {
                $('#payment_consent_alert').show();
                return;
            }

            $(this).closest("form").submit();

        });
    },
    b: function () {

        $('.agree-before-submit[type="checkbox"]').click(checkAgreeBeforeSubmit);

        checkAgreeBeforeSubmit();

        // 利用規約チェック 複数で使用する場合first_order/app_devに移動
        function checkAgreeBeforeSubmit() {
            var count = $('.agree-before-submit[type="checkbox"]').length;
            if (0 < count) {
                // disabled
                $('#js-agreement_on_page button[type=submit]').attr('disabled', 'true');
                $(".agree-submit").css('opacity', '0.5');

                // disabled状態のボタンにクリック要素をラップ
                $('#js-submit_disabled_wrapper').removeClass('disabled');
                $('#js-submit_disabled_wrapper').addClass('active');
                if (count === $('.agree-before-submit[type="checkbox"]:checked').length) {
                    // abled
                    $('#js-agreement_on_page button[type=submit]').attr('disabled', null);
                    $(".agree-submit").css('opacity', '1.0');

                    // disabled状態のボタンにクリック要素をラップ
                    $('#js-submit_disabled_wrapper').removeClass('active');
                    $('#js-submit_disabled_wrapper').addClass('disabled');

                    // チェックバリデーション非表示
                    // バリデーションクラスを足し引きしないとページ内エラーとして検知されてしまう
                    $('#js-remember_validation').removeClass('validation');
                    $('#js-remember_validation').hide();
                }
            }
        }
    },
    c: function () {
        $(function(){$('#js-submit_disabled_wrapper').click(function (evt) {

            // チェック状態を確認
            var count = $('.agree-before-submit[type="checkbox"]:checked').length;
            if (1 > count) {
                // チェックされていない チェックバリデーション表示
                // バリデーションクラスを足し引きしないとページ内エラーとして検知されてしまう
                $('#js-remember_validation').addClass('validation');
                $('#js-remember_validation').show();
            }
        });})
    },
}

var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonOrderReferenceId: '',
    buyerBillingAgreementConsentStatus: false,

    a: function () {

        console.log("AppAmazonPayWallet : a");


        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPayWallet.ClientId);

            // アドレスWidgetを表示
            new OffAmazonPayments.Widgets.AddressBook({
                sellerId: AppAmazonPayWallet.SELLER_ID,

                // Widgets起動状態
                onReady: function(billingAgreement) {

                    //************* test log *************
                    if ($('#addressBookWidgetDiv')[0]) {
                        //console.log("addressBookWidgetDiv is found.");
                    } else {
                        console.log("addressBookWidgetDiv is NOT found.");
                    }
                    //************* test log *************
                    
                    AppAmazonPayWallet.AmazonOrderReferenceId = billingAgreement.getAmazonOrderReferenceId();
                },
                // 住所選択変更時
                onAddressSelect: function () {
                    // do stuff here like recalculate tax and/or shipping
                    // お届希望日を取得
                    //AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonOrderReferenceId);

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

/*
 * document ready
 * */
$(function()
{
  AppAmazonPay.a();
  AppAmazonPay.b();
  AppAmazonPay.c();
  AppAmazonPayWallet.a();
});
