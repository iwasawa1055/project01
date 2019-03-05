var AppInputOrder =
{
  g: function()
  {
    // validation メッセージが表示される時に、ページ上部に表示する
    if ($('span').hasClass('validation')) {
      $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.dev-form');
    }
  },
  h: function()
  {
    $('.execute').on('click', function (e) {
      var is_update = $('#is_update').val();
      // カード更新
      if (is_update === '1') {
        gmoCreditCardPayment.setGMOTokenAndUpdateCreditCard();
        // カード登録
      } else {
        gmoCreditCardPayment.setGMOTokenAndRegisterCreditCard();
      }

    });
  },
  i: function()
  {
    // 初回表示時
    var address_id = $('#address_id').val();
    if (address_id !== 'add') {
      $('.order-input-address').hide();
    }
    // 選択住所変更時
    $('#address_id').change(function() {
      $('.order-input-address').hide();
      if ($(this).val() === 'add') {
        $('.order-input-address').slideDown(Speed, Ease);
      } else {
        $('.order-input-address').slideUp(Speed, Ease);
      }
    });
  },
  j: function()
  {
    var cc = $('#input-cc');
    var sc = $('#input-sc');
    var nc = $('#input-nc');

    var is_update = $('#is_update').val();
    // カード更新
    if (is_update === '1') {
      cc.hide();
      nc.hide();
      // カード登録
    } else {
      cc.hide();
      sc.hide();
      $('.input-check-list').hide();
    }

    $('.card_check_type').change(function() {
      if ($(this).val() === 'as-card') {
        sc.slideDown(Speed, Ease);
        cc.slideUp(Speed, Ease);
        nc.slideUp(Speed, Ease);
      }
      if ($(this).val() === 'change-card') {
        cc.slideDown(Speed, Ease);
        sc.slideUp(Speed, Ease);
        nc.slideUp(Speed, Ease);
      }
    });
  },
  k: function ()
  {
    $('#hanger_check input').change(function () {
      if ($(this).prop('checked')) {
        $('#execute').css("opacity", "1");

        if ($('#hanger-check-error').length) {
          $('#hanger-check-error').remove();
        }
      } else {
        $('#execute').css("opacity", "0.5");
      }
    });
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
  init_disp2: function () {
    // タイプ別箱の合計値設定
    var box_type_list = [
      {class : 'box_type_hanger',   id : 'hanger_total'  , flag : 'hanger'},
      {class : 'box_type_hako',     id : 'hako_total'    , flag : 'other'},
      {class : 'box_type_mono',     id : 'mono_total'    , flag : 'other'},
      {class : 'box_type_library',  id : 'library_total' , flag : 'other'},
      {class : 'box_type_cleaning', id : 'cleaning_total', flag : 'other'},
    ];
    var flag_total = [];
    flag_total['other']  = 0;
    flag_total['hanger'] = 0;
    $.each(box_type_list, function (index, box) {
      var type_total = 0;
      $('.' + box.class).each(function () {
        var type_value = $(this).val();
        if ($.isNumeric(type_value)) {
          type_total += parseFloat(type_value);
        }
      });
      // タイプ別箱合計値セット
      $('#' + box.id).html(type_total);
      // 購入タイプ(API)
      flag_total[box.flag] += parseFloat(type_total);
    });

    // hanger other 出力エリア
    if (flag_total['hanger'] > 0 && flag_total['other'] > 0) {
      $('.select_other').show();
      $('.select_hanger').show();
    } else if (flag_total['hanger'] > 0 && flag_total['other'] == 0) {
      $('.select_other').hide();
      $('.select_hanger').show();
    } else {
      $('.select_other').show();
      $('.select_hanger').hide();
    }

    var valueStep = 1;
    var minValue  = 0;
    var maxValue  = 20;
    // var flagType  = '';
    $('.btn-spinner').on('mousedown', function() {

      // ハンガー用出力エリア制御
      if($(this).closest(".type_other").length > 0){
        flagType = 'other';
      } else {
        flagType = 'hanger';
      }
      var itemValue  = parseInt($(this).parents('.spinner').find('.input-spinner').val());
      var btnType  = $(this).attr('name');
      if (btnType === 'spinner_down') {
        if (itemValue > minValue) {
          flag_total[flagType] = parseInt(flag_total[flagType]) - valueStep;
        }
      }
      if (btnType === 'spinner_up') {
        if (itemValue < maxValue) {
          flag_total[flagType] = parseInt(flag_total[flagType]) + valueStep;
        }
      }
      if (flag_total['hanger'] > 0 && flag_total['other'] > 0) {
        $('.select_other').show('slow');
        $('.select_hanger').show('slow');
      } else if (flag_total['hanger'] > 0 && flag_total['other'] == 0) {
        $('.select_other').hide('slow');
        $('.select_hanger').show('slow');
      } else {
        $('.select_other').show('slow');
        $('.select_hanger').hide('slow');
      }

      // 確認画面遷移ボタン制御
      if (flag_total['hanger'] > 0) {
        if ($('#hanger_check input').prop('checked')) {
          $('#execute').css("opacity", "1");
        } else {
          $('#execute').css("opacity", "0.5");
        }
      } else {
        $('#execute').css("opacity", "1");
      }
    });
    return false;
  },
  init_disp3: function () {
    // ハンガー時にボタンを薄くする
    if ($('.select_hanger').css('display') != 'none') {
      $('#execute').css("opacity", "0.5");
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

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
          // ハンガー未選択時
          if ($('.select_hanger').css('display') == 'none') {
            var self = $(this);
            var add_billing  = $('<input type="hidden" name="PaymentAmazonKitAmazonPay[amazon_order_reference_id]">');
            add_billing.val(AppAmazonPayWallet.AmazonOrderReferenceId);
            add_billing.insertAfter(self);

            // サブミット前チェック確認
            // 定期購入未チェックでエラー
            if(AppAmazonPayWallet.buyerBillingAgreementConsentStatus == 'false') {
              $('#payment_consent_alert').show();
              return;
            }
            $(this).closest("form").submit();
          } else {
            // ハンガー選択時
            if ($('#hanger_check input').prop('checked')) {
              var other_total  = parseInt($('#hako_total').html());
              var hanger_total = parseInt($('#hanger_total').html());
              other_total  = other_total + parseInt($('#mono_total').html());
              other_total  = other_total + parseInt($('#library_total').html());
              other_total  = other_total + parseInt($('#cleaning_total').html());
              if (other_total > 0 && hanger_total > 0) {
                alert('クローゼットと他の商品は同時購入できません');
                return false;
              } else {
                var self = $(this);
                var add_billing  = $('<input type="hidden" name="PaymentAmazonKitAmazonPay[amazon_order_reference_id]">');
                add_billing.val(AppAmazonPayWallet.AmazonOrderReferenceId);
                add_billing.insertAfter(self);

                // サブミット前チェック確認
                // 定期購入未チェックでエラー
                if(AppAmazonPayWallet.buyerBillingAgreementConsentStatus == 'false') {
                  $('#payment_consent_alert').show();
                  return;
                }
                $(this).closest("form").submit();
              }
            } else {
              if ($('#hanger-check-error').length == 0) {
                $('#hanger_check').parent('label').parent('li').append('<p class="valid-il" id="hanger-check-error">お届け日時のご確認をお願いします。</p>');
              }
            }
          }
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
    ajax_dateime: function () {
        var elem_datetime = $('#datetime_cd');

        $('option:first', elem_datetime).prop('selected', true);
        elem_datetime.attr("disabled", "disabled");

        // 引数取得
        var params = {};
        params.amazon_order_reference_id = AppAmazonPayWallet.AmazonOrderReferenceId;

        // API実行
        if (params.postal != '') {
            $.ajax({
                url: '/Order/as_get_address_datetime_by_amazon',
                cache: false,
                data: params,
                dataType: 'json',
                type: 'POST'
            }).done(function (data, textStatus, jqXHR) {
              elem_datetime.empty();
              $.each(data.result.results, function (index, datatime) {
                elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
              });
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
    AmazonOrderReferenceId: '',
    buyerBillingAgreementConsentStatus: false,
    AmazonWidgetReadyFlag: false,

    a: function () {
        // amazon Widget Ready
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId(AppAmazonPayWallet.ClientId);
            AppAmazonPayWallet.b();
        };
    },
    b: function () {
        // アドレスWidgetを表示
        new OffAmazonPayments.Widgets.AddressBook({
            sellerId: AppAmazonPayWallet.SELLER_ID,

            onOrderReferenceCreate: function(orderReference) {
                // Here is where you can grab the Order Reference ID.
                AppAmazonPayWallet.AmazonOrderReferenceId = orderReference.getAmazonOrderReferenceId();
            },
            // Widgets起動状態
            onReady: function() {
                AppAmazonPayWallet.AmazonWidgetReadyFlag = true;

                // カード選択 Widgetを表示
                new OffAmazonPayments.Widgets.Wallet({
                    sellerId: AppAmazonPayWallet.SELLER_ID,
                    design: {
                        designMode: 'responsive'
                    },
                    // カード選択変更時
                    onPaymentSelect: function (orderReference) {
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
                AppAmazonPay.ajax_dateime();

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
    }
}

// amazonpay callback
AppAmazonPayWallet.a();

/*
 * document ready
 * */
$(function()
{
  AppInputOrder.g();
  AppInputOrder.h();
  AppInputOrder.i();
  AppInputOrder.j();
  AppInputOrder.k();
  AppInputOrder.init_disp1();
  AppInputOrder.init_disp2();
  AppInputOrder.init_disp3();
  AppAddOrder.a();
  AppAddOrder.b();
  AppAmazonPay.a();
  AppAmazonPay.b();
  AppAmazonPay.c();
});
