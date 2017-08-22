var AppInputOrder =
{
  DELIVERY_ID_PICKUP : '6',
  DELIVERY_ID_MANUAL : '7',

  a: function(){

    var elem_day = $('#InboundDayCd');
    var elem_time = $('#InboundTimeCd');

    if(elem_day.val() === null) {
      $('option:first', elem_day).prop('selected', true);
      elem_day.attr("disabled", "disabled");
      elem_day.empty();
      $('option:first', elem_time).prop('selected', true);
      elem_time.attr("disabled", "disabled");
      elem_time.empty();

      $.post('/inbound/box/getInboundDatetime', {
            Inbound: {delivery_carrier: '6_1'}
          },
          function (data) {
            if (data.result.date) {
              var optionItems = new Array();
              $.each(data.result.date, function () {
                optionItems.push(new Option(this.text, this.date_cd));
              });
              elem_day.append(optionItems);

              $('#select_delivery_day').val(JSON.stringify(data.result.date));
            }
            ;
            if (data.result.time) {
              var optionItems = new Array();
              $.each(data.result.time, function () {
                optionItems.push(new Option(this.text, this.time_cd));
              });
              elem_time.append(optionItems);

              $('#select_delivery_time').val(JSON.stringify(data.result.time));
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
    // 預け入れ方法の選択初期化
    if ($('span').hasClass('validation')) {
      $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.panel-default');
    }

  },
}


var AppAmazonPay =
{
    DELIVERY_ID_PICKUP : '6',
    DELIVERY_ID_MANUAL : '7',

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            var self = $(this);
            var add_billing  = $('<input type="hidden" name="amazon_billing_agreement_id">');
            add_billing.val(AppAmazonPayWallet.AmazonBillingAgreementId);
            add_billing.insertAfter(self);

            // サブミット前チェック確認
            // 定期購入未チェックでエラー
            if(AppAmazonPayWallet.buyerBillingAgreementConsentStatus == 'false') {
                $('#payment_consent_alert').show();
                return;
            }

            $(this).closest("form").submit();

            console.log("AppAmazonPay : a");
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
    ajax_dateime: function(amazon_billing_agreement_id){

        var elem_day = $('#InboundDayCd');
        var elem_time = $('#InboundTimeCd');

        if(elem_day.val() === null) {
            $('option:first', elem_day).prop('selected', true);
            elem_day.attr("disabled", "disabled");
            elem_day.empty();
            $('option:first', elem_time).prop('selected', true);
            elem_time.attr("disabled", "disabled");
            elem_time.empty();

            $.post('/DirectInbound/as_getInboundDatetime', {
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
    }
}

var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonBillingAgreementId: '',
    buyerBillingAgreementConsentStatus: false,

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
                    // AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonBillingAgreementId);

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
                                    AppAmazonPayWallet.buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus(); // getConsentStatus returns true or false
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
                    AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonBillingAgreementId);

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
  AppInputOrder.a();
  AppInputOrder.b();
  AppInputOrder.c();
  AppAmazonPay.a();
  AppAmazonPay.b();
  AppAmazonPay.c();
  AppAmazonPayWallet.a();
});

