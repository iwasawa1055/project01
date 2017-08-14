var AppAmazonPayment =
{

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            var self = $(this);
            var add_reference  = $('<input type="hidden" name="order_reference_id">');
            add_reference.val(AppAmazonPaymentWallet.orderReferenceId);
            add_reference.insertAfter(self);

            var add_billing  = $('<input type="hidden" name="amazon_billing_agreement_id">');
            add_billing.val(AppAmazonPaymentWallet.AmazonBillingAgreementId);
            add_billing.insertAfter(self);

            // サブミット前チェック確認
            // 定期購入未チェックでエラー
            if(AppAmazonPaymentWallet.buyerBillingAgreementConsentStatus == 'false') {
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
    ajax_dateime: function (amazon_billing_agreement_id) {
        var elem_datetime = $('#datetime_cd');

        $('option:first', elem_datetime).prop('selected', true);
        elem_datetime.attr("disabled", "disabled");

        // 引数取得
        var params = {};
        params.amazon_billing_agreement_id = amazon_billing_agreement_id;

        // API実行
        if (params.postal != '') {
            $.ajax({
                url: '/FirstOrder/as_get_address_datetime_by_amazon',
                cache: false,
                data: params,
                dataType: 'json',
                type: 'POST'
            }).done(function (data, textStatus, jqXHR) {
                $('#datetime_cd > option').remove();
                // 成功時 お届け日時セット
                elem_datetime.append($('<option>').html('以下からお選びください').val(''));
                $.each(data.results, function (index, datatime) {
                    elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
                });
                // 戻る対応でリストをpostする
                $('#select_delivery').val(JSON.stringify(data.results));
            }).fail(function (data, textStatus, errorThrown) {
                // 失敗時 お届け日時リセット
                $('#datetime_cd > option').remove();
                $('#datetime_cd').append($('<option>').html('以下からお選びください').val(''));
            }).always(function (data, textStatus, returnedObject) {
                elem_datetime.removeAttr("disabled");
                //  $('body').airLoader().end();
            });
        } else {
            // お届け日時リセット
            $('#datetime_cd > option').remove();
            $('#datetime_cd').append($('<option>').html('以下からお選びください').val(''));
            elem_datetime.removeAttr("disabled");
        }


    }
}


var AppAmazonPaymentWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonBillingAgreementId: '',
    orderReferenceId: '',
    buyerBillingAgreementConsentStatus: false,

    a: function () {
        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPaymentWallet.ClientId);

            // アドレスWidgetを表示
            new OffAmazonPayments.Widgets.AddressBook({
                sellerId: AppAmazonPaymentWallet.SELLER_ID,
                agreementType: 'BillingAgreement',

                // Widgets起動状態
                onReady: function(billingAgreement) {
                    AppAmazonPaymentWallet.AmazonBillingAgreementId = billingAgreement.getAmazonBillingAgreementId();
                    AppAmazonPaymentWallet.orderReferenceId = billingAgreement.getAmazonBillingAgreementId();

                    // お届希望日を取得
                    AppAmazonPayment.ajax_dateime(AppAmazonPaymentWallet.AmazonBillingAgreementId);

                    // カード選択 Widgetを表示
                    new OffAmazonPayments.Widgets.Wallet({
                        sellerId: AppAmazonPaymentWallet.SELLER_ID,
                        amazonBillingAgreementId: AppAmazonPaymentWallet.AmazonBillingAgreementId,
                        design: {
                            designMode: 'responsive'
                        },
                        onReady: function() {
                            // 定期購入チェックを確認
                            new OffAmazonPayments.Widgets.Consent({
                                sellerId: AppAmazonPaymentWallet.SELLER_ID,
                                amazonBillingAgreementId: AppAmazonPaymentWallet.AmazonBillingAgreementId,

                                // amazonBillingAgreementId obtained from the Amazon Address Book widget.
                                design: {
                                    designMode: 'responsive'
                                },
                                onReady: function(billingAgreementConsentStatus){

                                    // Called after widget renders
                                    AppAmazonPaymentWallet.buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus(); // getConsentStatus returns true or false
                                    // true – checkbox is selected
                                    // false – checkbox is unselected - default
                                },
                                onConsent: function(billingAgreementConsentStatus) {
                                    AppAmazonPaymentWallet.buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus();
                                    // getConsentStatus returns true or false
                                    // true – checkbox is selected – buyer has consented
                                    // false – checkbox is unselected – buyer has not consented
                                    // Replace this code with the action that you want to perform
                                    // after the consent checkbox is selected/unselected.
                                },
                                onError: function(error) {
                                    console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                                }
                            }).bind("consentWidgetDiv ");
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
                    AppAmazonPayment.ajax_dateime(AppAmazonPaymentWallet.AmazonBillingAgreementId);

                },
                design: {
                    designMode: 'responsive'
                },
                onError: function (error) {
                    console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
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
    AppAmazonPayment.a();
    AppAmazonPayment.b();
    AppAmazonPayment.c();
    AppAmazonPaymentWallet.a();
});