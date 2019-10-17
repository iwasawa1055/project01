var AppInboundAttention =
{
  init: function() {

    // 集荷日時を取得
    AppInboundAttention.getDatetime();

    // エラースクロール
    AppInboundAttention.scrollValidError();

    // modal表示
    if ( document.referrer.indexOf('/inbound/box/attention_amazon_pay') == -1 && document.referrer.indexOf('/inbound/box/confirm_amazon_pay') == -1) {
      $("[data-remodal-id=packaging]").remodal().open();
    }
  },

  cautionBox: function ()
  {
    // 注意事項
    if($('.caution-box').length){
      // ボタンを非活性
      $('#execute').css('opacity','0.3');
      $('#execute').prop('disabled',true);
    }

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
          $('#execute').prop('disabled',false);
        }
      } else {
        $('#execute').css("opacity", "0.5");
        $('#execute').prop('disabled',false);
      }
    });
  },
  submitForm: function ()
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

        // amazon order reference id
        var add_billing  = $('<input type="hidden" name="data[InboundBase][amazon_order_reference_id]">');
        add_billing.val(AppAmazonPayWallet.AmazonOrderReferenceId);
        $("form").append(add_billing);
        document.form.submit();
      }
    });
  },

  scrollValidError: function () {
    var img_num = $('.img-item').length;
    var img_counter = 0;
    for (var i = 0; i < img_num; i++) {
      var img = $('<img>');
      img.load(function() {
        img_counter++;
        // 全てのボックス画像を出力し終えた際に実施
        if (img_num == img_counter) {
          var valid = $(".valid-il");
          if (valid.length > 0) {
            if ($(valid).closest('div.box-info').length > 0) {
              // ボックス系のエラー
              var position = valid.parent().parent().offset().top;
            } else {
              // 入力系のエラー
              var position = valid.parent().offset().top;
            }
            $('body,html').animate({scrollTop: position}, 'slow');
          }
        }
      });
      img.attr('src', $('img').eq(i).attr('src'));
    }
  },

  getDatetime: function () {
    var elem_address = $('#address_id');
    var elem_datetime = $('#datetime_cd');
    var week_text = ["(日)", "(月)", "(火)", "(水)", "(木)", "(金)", "(土)"];
    var select_datetime_cd = $('#select_datetime_cd').val();

    $.ajax({
      url      : '/ajax/as_getYamatoDatetime',
      cache    : false,
      dataType : 'json',
      type     : 'POST'
    }).done(function (data, textStatus, jqXHR) {
      elem_datetime.empty();
      $.each(data.results, function (index, datatime) {
        var date_obj = new Date(index);
        var week = date_obj.getDay();
        $.each(datatime, function (datatime_key, datatime_text) {
          var time_text = index.replace(/-/g, '/') + ' ' + week_text[week] + ' ' + datatime_text;
          var time_cd   = index + '-' + datatime_key;
          elem_datetime.append($('<option>').html(time_text).val(time_cd));
        });
      });
    }).fail(function (data, textStatus, errorThrown) {
    }).always(function (data, textStatus, returnedObject) {
      elem_datetime.removeAttr("disabled");
      // 選択
      if (select_datetime_cd.length) {
        elem_datetime.val(select_datetime_cd);
      } else {
        elem_datetime.prop("selectedIndex", 0);
      }
    });
  },
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
        // Widgets起動状態
        onReady: function(billingAgreement) {
          AppAmazonPayWallet.AmazonWidgetReadyFlag = true;
          AppAmazonPayWallet.AmazonOrderReferenceId = billingAgreement.getAmazonOrderReferenceId();
        },
        onAddressSelect: function () {
          AppAmazonUserNameDevide.a(AppAmazonPayWallet.AmazonOrderReferenceId);
        },
        design: {
          designMode: 'responsive'
        },
        onError: function (error) {
          JsError.a("/inbound_box/attention_amazon_pay", error.getErrorCode() + error.getErrorMessage());
          if(error.getErrorCode() == 'BuyerSessionExpired') {
            amazon.Login.logout();
            location.href = '/login/logout';
          }
        }
      }).bind("addressBookWidgetDiv");
    }
  }

var AppAmazonUserNameDevide =
  {
    a: function(amazon_order_reference_id)
    {
      $.post('/InboundBox/as_get_amazon_user_info_detail', {
          amazon_order_reference_id: amazon_order_reference_id
        },

        function (data) {
          AppAmazonUserNameDevide.removeNameFormValue();

          if (data.status) {
            AppAmazonUserNameDevide.setNameFormValue(data.name.lastname, data.name.firstname);
          } else {
            //AmazonPayからの名前データ取得失敗時は入力フォーム表示
            var elem_deca = $('#InboundDeliveryCarrier');
            if (elem_deca.val().indexOf(DELIVERY_ID_PICKUP) !== -1) {
              AppAmazonUserNameDevide.showNameForm();
            }
          }
        }
        ,
        'json'
      );
    },
    showNameForm: function()
    {
      $(".name-form-group").css('display', 'block');
    },
    hideNameForm: function()
    {
      $(".name-form-group").css('display', 'none');
    },
    setNameFormValue: function(lastname, firstname)
    {
      $(".lastname").val(lastname);
      $(".firstname").val(firstname);
      AppAmazonUserNameDevide.hideNameForm();
    },
    removeNameFormValue: function()
    {
      $(".lastname").val('');
      $(".firstname").val('');
    },
  }

/*
 * document ready
 * */
$(function()
{
  AppInboundAttention.init();
  AppInboundAttention.cautionBox();
  AppInboundAttention.submitForm();
});

// Amazonpay Callback
AppAmazonPayWallet.a();

// モーダルでエラーが発生するので打ち消し
function scrollTo(_target,_correction,_speed) {}