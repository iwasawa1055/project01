var AppInputOrder =
{
  g: function()
  {
    // validation メッセージが表示される時に、ページ上部に表示する
    if ($('span').hasClass('validation')) {
      $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.dev-wrapper');
    }
  },
  init_disp1: function () {
    // 合計点数の初期化
    var total_number = Number(0);
    $('.js-item-number').each(function () {
      var set_number = $(this).val();
      var selector_name = $(this).data("name");
      $('input[name='+ selector_name + ']').val(set_number);
      total_number += Number(set_number);
      // console.log('number:' + number);
    });

    if (total_number === 0) {
      $('#js-item-total').html('0点');
    } else {
      $('#js-item-total').html(total_number +'点');
    }
  },

  getDatetime: function () {
    var elem_address = $('#address_id');
    var elem_datetime = $('#datetime_cd');

    // 未選択また「追加」を選択
    if (!elem_address.val() || elem_address.val() == -99) {
      $('.dsn-input-new-adress').show('slow');
      elem_datetime.empty();
      return;
    }

    // アドレス入力を非表示
    $('.dsn-input-new-adress').hide('slow');

    $('option:first', elem_datetime).prop('selected', true);
    elem_datetime.attr("disabled", "disabled");

    $.post('/order/getAddressDatetime',
      { address_id: elem_address.val() },
      function(data){
        if (data.result) {
          elem_datetime.empty();
          var optionItems = new Array();
          $.each(data.result, function() {
              optionItems.push(new Option(this.text, this.datetime_cd));
          });
          // 戻る対応でリストをpostする
          $('#select_delivery').val(JSON.stringify(data.result));

          elem_datetime.append(optionItems);
        };
      },
      'json'
    ).always(function() {
      elem_datetime.removeAttr("disabled");
    });
  },
  getDatetimePostal: function (){
      var elem_postal = $('#postal');
      var elem_datetime = $('#datetime_cd');

      $('option:first', elem_datetime).prop('selected', true);
      elem_datetime.attr("disabled", "disabled");

      // 引数取得
      var params = {};
      params.postal = elem_postal.val();

      // API実行
      if (params.postal != '') {
        $.ajax({
          url: '/order/as_get_address_datetime_by_postal',
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
    },
}

// FirstOrderから移植
var AppAddOrder =
{
  a: function () {
    // ボックス数選択
    $('.js-item-number').change(function () {
      var selector = $(this).data("box_type");
      var number = Number(0);
      var total_number = Number(0);

      $('.js-item-'+ selector).each(function () {
        var set_number = $(this).val();
        var selector_name = $(this).data("name");
        $('input[name='+ selector_name + ']').val(set_number);
        number += Number(set_number);
        // console.log('number:' + number);
      });

      $('.js-item-number').each(function () {
        var set_number = $(this).val();
        var selector_name = $(this).data("name");
        $('input[name='+ selector_name + ']').val(set_number);
        total_number += Number(set_number);
        // console.log('number:' + number);
      });

      if (number === 0) {
        $('#select_' + selector).html('未選択');
      } else {
        $('#select_' + selector).html('<span>' +  number +'個選択済み</span>');
      }

      if (total_number === 0) {
        $('#js-item-total').html('0点');
      } else {
        $('#js-item-total').html(total_number +'点');
      }
    });
  },

  b: function () {
    $('.btn-submit').on('click', function (e) {
      $('form').submit();
    });
  },
}

var AppAmazonPay =
{
    DELIVERY_ID_PICKUP : '6',
    DELIVERY_ID_MANUAL : '7',
    
    
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
                url: '/order/as_get_address_datetime_by_amazon',
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

var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonBillingAgreementId: '',
    orderReferenceId: '',
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
                    AppAmazonPayWallet.orderReferenceId = billingAgreement.getAmazonBillingAgreementId();

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

  AppInputOrder.g();
  AppInputOrder.init_disp1();
  AppAddOrder.a();
  AppAddOrder.b();
  AppAmazonPayWallet.a();
});
