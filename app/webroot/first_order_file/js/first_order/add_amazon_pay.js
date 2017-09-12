var AppAmazonPay =
{

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            var self = $(this);

            var add_billing  = $('<input type="hidden" name="amazon_billing_agreement_id">');
            add_billing.val(AppAmazonPayWallet.AmazonBillingAgreementId);
            add_billing.insertAfter(self);

            // サブミット前チェック確認
            // 定期購入未チェックでエラー
            if(AppAmazonPayWallet.buyerBillingAgreementConsentStatus != 'false') {
                $(this).closest("form").submit();
                return;
            }

            if( !$('div.dev-divider').prev('div').children('span').hasClass('billing-agreement')) {
                $('<div class="dsn-form"><span class="validation billing-agreement">お支払方法の設定は必須です。</span></div>').insertBefore('div.dev-divider');
            }

            alert('Amazon Pay をお支払方法に設定する 同意は必須です。');
            return false;
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
    d: function () {
        //** Auto Kana
        $('input.lastname').airAutoKana(
        {
            dest: 'input.lastname_kana',
            katakana: true
        });

        $('input.firstname').airAutoKana(
            {
                dest: 'input.firstname_kana',
                katakana: true
            });
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

                // 選択済保持
                if ($('#js-datetime_cd').val() != '') {
                    $("#datetime_cd").val($('#js-datetime_cd').val());
                }

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


var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonBillingAgreementId: '',
    buyerBillingAgreementConsentStatus: false,
    ErrorMessage: '一時的に Amazon Pay がご利用いただけません。「戻る」ボタンより前の画面へ戻り、再度お試しください。何度もこのメッセージが表示される場合はクレジットカードでご購入ください。',

    a: function () {
        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPayWallet.ClientId);

            // アドレスWidgetを表示
            new OffAmazonPayments.Widgets.AddressBook({
                sellerId: AppAmazonPayWallet.SELLER_ID,
                agreementType: 'BillingAgreement',

                // Widgets起動状態
                onReady: function(billingAgreement) {
                    AppAmazonPayWallet.AmazonBillingAgreementId = billingAgreement.getAmazonBillingAgreementId();

                    // お届希望日を取得
                    AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonBillingAgreementId);

                    // カード選択 Widgetを表示
                    new OffAmazonPayments.Widgets.Wallet({
                        sellerId: AppAmazonPayWallet.SELLER_ID,
                        amazonBillingAgreementId: AppAmazonPayWallet.AmazonBillingAgreementId,
                        design: {
                            designMode: 'responsive'
                        },
                        onReady: function() {
                            // 定期購入チェックを確認
                            new OffAmazonPayments.Widgets.Consent({
                                sellerId: AppAmazonPayWallet.SELLER_ID,
                                amazonBillingAgreementId: AppAmazonPayWallet.AmazonBillingAgreementId,

                                // amazonBillingAgreementId obtained from the Amazon Address Book widget.
                                design: {
                                    designMode: 'responsive'
                                },
                                onReady: function(billingAgreementConsentStatus){
                                    // Called after widget renders
                                    // エラー回避
                                    if(typeof billingAgreementConsentStatus.getConsentStatus == 'function') {
                                        AppAmazonPayWallet.buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus(); // getConsentStatus returns true or false
                                    }
                                    // true – checkbox is selected
                                    // false – checkbox is unselected - default
                                },
                                onConsent: function(billingAgreementConsentStatus) {
                                    AppAmazonPayWallet.buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus();
                                    // getConsentStatus returns true or false
                                    // true – checkbox is selected – buyer has consented
                                    // false – checkbox is unselected – buyer has not consented
                                    // Replace this code with the action that you want to perform
                                    // after the consent checkbox is selected/unselected.
                                },
                                onError: function(error) {
                                    $('#error_alert').append(AppAmazonPayWallet.ErrorMessage);
                                    $('#error_alert').show();
                                    $('#dsn-amazon-pay').hide();
                                    $('#dsn-payment').hide();
                                    amazon.Login.logout();
                                }
                            }).bind("consentWidgetDiv ");
                        },
                    // カード選択変更時
                        onPaymentSelect: function () {
                        },
                        onError: function (error) {
                            $('#error_alert').append(AppAmazonPayWallet.ErrorMessage);
                            $('#error_alert').show();
                            $('#dsn-amazon-pay').hide();
                            $('#dsn-payment').hide();
                            amazon.Login.logout();
                        }
                    }).bind("walletWidgetDiv");

                },
                // 住所選択変更時
                onAddressSelect: function () {
                    // do stuff here like recalculate tax and/or shipping
                    // お届希望日を取得
                    AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonBillingAgreementId);

                },
                design: {
                    designMode: 'responsive'
                },
                onError: function (error) {
                    $('#error_alert').append(AppAmazonPayWallet.ErrorMessage);
                    $('#error_alert').show();
                    $('#dsn-amazon-pay').hide();
                    $('#dsn-payment').hide();
                    amazon.Login.logout();
                }
            }).bind("addressBookWidgetDiv");


        };
    }
}

var AppInputOrder =
{
  g: function()
  {
    // validation メッセージが表示される時に、ページ上部に表示する
    if ($('span').hasClass('validation')) {
      $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.dev-wrapper');
    }
  },
}

/*
 * document ready
 * */
$(function()
{
    AppAmazonPay.a();
    AppAmazonPay.b();
    AppAmazonPay.c();
    AppAmazonPay.d();
    AppAmazonPayWallet.a();
    AppInputOrder.g();
});
