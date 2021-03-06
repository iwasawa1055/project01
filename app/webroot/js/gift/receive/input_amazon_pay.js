var flag_total = [];
var AppInputOrder =
{
  a: function ()
  {
    $('.caution-box input').change(function () {
      if ($(this).prop('checked')) {
        var exe_flag = true;
        $(".caution-box input").each(function(i) {
          if ($(this).prop("checked") == false) {
            exe_flag = false;
            return false;
          }
        });
        if (exe_flag) {
          $('#execute').css("opacity", "1");
        }
      } else {
        $('#execute').css("opacity", "0.5");
      }
    });
  },
  b: function ()
  {
    $('#execute').on('mouseup', function(){
      var exe_flag = true;
      $(".caution-box input").each(function(i) {
        if (!$(this).prop("checked")) {
          exe_flag = false;
          return false;
        }
      });
      if (exe_flag) {
        $('.loader').airCenter();
        $('.airloader-overlay').show();
        $(this).closest("form").submit();
      }
    });
  },
  c: function()
  {
    // validation メッセージが表示される時に、ページ上部に表示する
    if ($('p').hasClass('valid-il')) {
      $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.head_validation');
    }
  },
  d: function()
  {
    var valid = $(".valid-il").get(0);
    if (valid) {
      var position = valid.offsetTop;
      $('body,html').animate({scrollTop: position}, 'slow');
    }
  },
  e: function ()
  {
    // ギフトコード確認ボタン押下
    $('#check_gift_cd').on('click', function (e) {

      // ギフトコードエラー削除
      $('.input_gift_cd_area > p.valid-il').remove();
      // ギフト説明(h4)削除
      $('h4#gift_head_message').remove();

      // ギフト内容を非表示
      $(".item").each(function(i) {
        $(this).hide();
      });

      var gift_cd = $('#gift_cd').val();

      // API実行
      if (gift_cd) {

        // 引数取得
        var data = {
          "gift_cd" : gift_cd
        };

        $.ajax({
          url      : '/gift/receive/as_get_gift_data',
          cache    : false,
          data     : data,
          dataType : 'json',
          type     : 'POST'
        }).done(function (data, textStatus, jqXHR) {
          if (!data.status) {
            // 該当データなし
            $('.input_gift_cd_area').append('<p class="valid-il">該当するギフトコードが存在しません</p>');
          } else {
            // 該当データあり
            $('li.gift-info').before('<h4 id="gift_head_message">ご利用可能な商品</h4>');
            $.each(data.result, function (index, kit_data) {
              var target_kit_id = '#kit_' + kit_data.kit_cd;
              // 個数
              $(target_kit_id).find('p.text-number>span').text(kit_data.kit_cnt);
              // エリア表示
              $(target_kit_id).show();
            });
          }
        }).fail(function (data, textStatus, errorThrown) {
          $('.input_gift_cd_area').append('<p class="valid-il">画面をリロードしてください</p>');
        }).always(function (data, textStatus, returnedObject) {
        });

      } else {
        // お届け日時リセット
        $('.input_gift_cd_area').append('<p class="valid-il">ギフトコードを入力してください</p>');
      }
    });
  },

  init_disp1: function () {
    // タイプ別箱の合計値設定
    var box_type_list = [
      {class : 'box_type_hanger',   id : 'hanger_total'  , flag : 'hanger'},
      {class : 'box_type_hako',     id : 'hako_total'    , flag : 'other'},
      {class : 'box_type_mono',     id : 'mono_total'    , flag : 'other'},
      {class : 'box_type_library',  id : 'library_total' , flag : 'other'},
      {class : 'box_type_cleaning', id : 'cleaning_total', flag : 'other'},
    ];
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

    // お届け日時
    if (flag_total['other'] == 0 && flag_total['hanger'] > 0) {
      $('.select_other').hide();
    } else {
      $('.select_other').show();
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
      // お届け日時
      if (flag_total['other'] == 0 && flag_total['hanger'] > 0) {
        $('.select_other').hide('slow');
      } else {
        $('.select_other').show('slow');
      }
    });
    return false;
  },
  init_disp2: function () {
    // ハンガー時にボタンを薄くする
    if ($('.caution-box').css('display') != 'none') {
      $('#execute').css("opacity", "0.5");
    }
  },

}

var AppAmazonPay =
{
    DELIVERY_ID_PICKUP : '6',
    DELIVERY_ID_MANUAL : '7',

    a: function () {
        $('.js-btn-submit').on('click', function (e) {

          var exe_flag = true;
          $(".caution-box input").each(function(i) {
            if (!$(this).prop("checked")) {
              exe_flag = false;
              return false;
            }
          });
          if (exe_flag) {
            var self = $(this);
            var add_billing  = $('<input type="hidden" name="ReceiveGiftByAmazonPay[amazon_order_reference_id]">');
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
  AppInputOrder.a();
  AppInputOrder.b();
  AppInputOrder.c();
  AppInputOrder.d();
  AppInputOrder.e();
  AppInputOrder.init_disp1();
  AppInputOrder.init_disp2();
  AppAmazonPay.a();
  AppAmazonPay.b();
  AppAmazonPay.c();
});
