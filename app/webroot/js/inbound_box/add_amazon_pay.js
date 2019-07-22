var AppInboundBaseBoxAdd =
{
  init: function() {
    // ボックスタイプ別に出力する内容を切り替える
    $('[name="data[InboundBase][box_type]"]').change(function() {
      if ($(this).val() === 'new') {
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev-input-box-type-new").show();
      } else if ($(this).val() === 'old') {
        $("input[name='data[InboundBase][delivery_carrier]']:eq(0)").prop('checked', true);
        $("#dev-self-delivery").hide();
        $("#dev-input-box-type-new").show();
        $('#dev-new-box').fadeOut(400, 'easeInOutExpo');
        $('#dev-old-box').fadeIn(400, 'easeInOutExpo');
      }
    });

    // 自分で発送する際は住所情報を非表示
    $('[name="data[InboundBase][delivery_carrier]"]').change(function() {
      if ($(this).val() === '7') {
        $("#dev-input-box-type-new").hide();
      } else {
        $("#dev-input-box-type-new").show();
      }
    });

    $("#execute").click("on", function(){
      var error = 0;
      var box_type = $('[name="data[InboundBase][box_type]"]:checked').val();
      if (typeof box_type === 'undefined' || box_type == "new") {
        $('#dev-new-box-grid > li').each(function(i1, v1){
          // チェックが付いている
          if ($(v1).children(".input-check").children('.cb-circle:checked').val() == 1) {
            if ($(v1).children(".box-info").children('.box-input-name').val() == '') {
              error = 1;
            }
          }
        });
      } else {
        $('#dev-old-box-grid > li').each(function(i1, v1){
          // チェックが付いている
          if ($(v1).children(".input-check").children('.cb-circle:checked').val() == 1) {
            if ($(v1).children(".box-info").children('.box-input-name').val() == '') {
              error = 1;
            }
          }
        });
      }

      // 選択済みでボックスタイトルが設定されていない
      if (error === 1) {
        alert('選択されたボックスにボックス名が入力されていません。');
        return false;
      }

      var add_billing  = $('<input type="hidden" name="data[InboundBase][amazon_order_reference_id]">');
      add_billing.val(AppAmazonPayWallet.AmazonOrderReferenceId);
      $("form").append(add_billing);
      document.form.submit();
    });

    // modal表示
    $("[data-remodal-id=packaging]").remodal().open();
  },

  checkSelectBox: function() {
    $('.dev-box-check').each(function(index) {
      if ($(this).prop("checked") == true) {
        $(this).parents('li').find('.box-input-name').addClass("item-checked");
        $(this).parents('li').find('.box-input-name').prop("disabled", false);
      } else {
        $(this).parents('li').find('.box-input-name').removeClass("item-checked");
        $(this).parents('li').find('.box-input-name').prop("disabled", true);
      }
    });

    $('.box-img-area').on('click', function (e) {
      if ($(this).find('.dev-box-check').prop("checked") == true) {
        $(this).parent().find('.box-input-name').addClass("item-checked");
        $(this).parent().find('.box-input-name').prop("disabled", false);
      } else {
        $(this).parent().find('.box-input-name').removeClass("item-checked");
        $(this).parent().find('.box-input-name').prop("disabled", true);
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
                JsError.a("/inbound_box/add_amazon_pay", error.getErrorCode() + error.getErrorMessage());
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
  setNameFormValue: function(lastname, firstname)
  {
    $("#lastname").val(lastname);
    $("#firstname").val(firstname);
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
  AppInboundBaseBoxAdd.init();
  AppInboundBaseBoxAdd.checkSelectBox();

  //集荷日を選択時に集荷時間をセットする
  PickupYamato.changeSelectPickup();

  // 集荷日と集荷時間取得
  PickupYamato.getDateTime();
});

// Amazonpay Callback
AppAmazonPayWallet.a();

// モーダルでエラーが発生するので打ち消し
function scrollTo(_target,_correction,_speed) {}
