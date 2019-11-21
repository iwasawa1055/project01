var AppAmazonPay =
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
        // 預け入れ方法の選択初期化
        if($("#yamato").prop('checked')) {
            $('.dsn-arrival').hide('fast');
            $('.dsn-yamato').show('fast');
        } else {
            $('.dsn-arrival').show('fast');
            $('.dsn-yamato').hide('fast');
        }
    },
    c: function () {

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
    d: function () {
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
    f: function () {
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
    ajax_dateime: function(amazon_billing_agreement_id){
        var week_text = ["(日)", "(月)", "(火)", "(水)", "(木)", "(金)", "(土)"];

        $.ajax({
          url: '/ajax/as_getYamatoDatetime',
          cache: false,
          dataType: 'json',
          type: 'POST'
        }).done(function (data, textStatus, jqXHR) {
            if (data.results) {
                pickup_date_time = data.results;
                $('#InboundDayCd option').remove();
                $('#InboundTimeCd option').remove();

                var pickup_date = null;
                // 集荷日をセット
                for (var item in pickup_date_time) {
                    // 最初の日付での時間を下でセットする
                    if (pickup_date == null) {
                        pickup_date = item;
                    }

                    // 集荷日程をセット
                    var date_obj = new Date(item);
                    var week = date_obj.getDay();
                    var pickup_date_text = item.replace(/-/g, '/') + ' ' + week_text[week]; 

                    $('#InboundDayCd').append($('<option>').text(pickup_date_text).attr('value', item));
                }

                // 戻るボタンで戻ってきた時は選択していた日付をselectedする
                if ($('#select_delivery_day').val() != '') {
                   $('#InboundDayCd').val($('#select_delivery_day').val());
                    pickup_date = $('#select_delivery_day').val();
                }

                // 時間をセット
                for(var item in pickup_date_time[pickup_date]) {
                    var pickup_time_text = pickup_date_time[pickup_date][item];
                    $('#InboundTimeCd').append($('<option>').text(pickup_time_text).attr('value', item));
                }

                // 戻るボタンで戻ってきた時は選択していた時間付をselectedする
                if ($('#select_delivery_time').val() != '') {
                   $('#InboundTimeCd').val($('#select_delivery_time').val());
                }
            }

        }).fail(function (data, textStatus, errorThrown) {
            console.log(data);
            $('#InboundDayCd').removeAttr("disabled");
            $('#InboundTimeCd').removeAttr("disabled");
        }).always(function (data, textStatus, returnedObject) {
            console.log(data);
            $('#InboundDayCd').removeAttr("disabled");
            $('#InboundTimeCd').removeAttr("disabled");
        });
    },
    change_pickup_date: function(){
        // 日付selectboxで変更した時
        $('#InboundDayCd').change(function() {
            var change_pickup_date = $('#InboundDayCd option:selected').val();
            $('#InboundTimeCd option').remove();
            for(var item in pickup_date_time[change_pickup_date]) {
                var pickup_time_text = pickup_date_time[change_pickup_date][item];
                $('#InboundTimeCd').append($('<option>').text(pickup_time_text).attr('value', item));
            }
        });
    }
}

var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonBillingAgreementId: '',
    buyerBillingAgreementConsentStatus: false,
    AmazonWidgetReadyFlag: false,

    a: function () {
        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPayWallet.ClientId);
            AppAmazonPayWallet.AmazonBillingAgreementId = $("#amazon_billing_agreement_id").val();
            AppAmazonPayWallet.b();
        };
    },
    b: function () {
        // アドレスWidgetを表示
        new OffAmazonPayments.Widgets.AddressBook({
            sellerId: AppAmazonPayWallet.SELLER_ID,
            agreementType: 'BillingAgreement',
            amazonBillingAgreementId: AppAmazonPayWallet.AmazonBillingAgreementId,

            // Widgets起動状態
            onReady: function(billingAgreement) {
                AppAmazonPayWallet.AmazonWidgetReadyFlag = true;
                if(AppAmazonPayWallet.AmazonBillingAgreementId === '') {
                    AppAmazonPayWallet.AmazonBillingAgreementId = billingAgreement.getAmazonBillingAgreementId();
                }

                // お届希望日を取得
                // AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonBillingAgreementId);

                // カード選択 Widgetを表示
                new OffAmazonPayments.Widgets.Wallet({
                    sellerId: AppAmazonPayWallet.SELLER_ID,
                    amazonBillingAgreementId: AppAmazonPayWallet.AmazonBillingAgreementId,
                    design: {
                        designMode: 'responsive'
                    },
                    onReady: function() {
                        //　初回のみ定期購入チェックのウィジェットを表示
                        if($('#regist_user_flg').val() == '0') {
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
                                    console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                                }
                            }).bind("consentWidgetDiv ");
                        }
                    },
                    // カード選択変更時
                    onPaymentSelect: function () {
                    },
                    onError: function (error) {
                        console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
                    }
                }).bind("walletWidgetDiv");

            },
            /*
            // 住所選択変更時
            onAddressSelect: function () {
                // do stuff here like recalculate tax and/or shipping
                // お届希望日を取得
                AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonBillingAgreementId);

            },
            */
            design: {
                designMode: 'responsive'
            },
            onError: function (error) {
                console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
            }
        }).bind("addressBookWidgetDiv");
    }
}

// Amazonpay callback
AppAmazonPayWallet.a();

/*
 * document ready
 * */
$(function()
{
    AppAmazonPay.a();
    AppAmazonPay.b();
    AppAmazonPay.c();
    AppAmazonPay.d();
    AppAmazonPay.f();
    PickupYamato.getDateTime();
    PickupYamato.changeSelectPickup();
});
