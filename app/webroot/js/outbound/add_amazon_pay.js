var AppAmazonPay =
{
   DELIVERY_ID_PICKUP : '6',
   DELIVERY_ID_MANUAL : '7',

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            $("#amazon_id").val(AppAmazonPayWallet.AmazonOrderReferenceId);

            // サブミット前チェック確認
            // 定期購入未チェックでエラー
            if(AppAmazonPayWallet.buyerBillingAgreementConsentStatus == 'false') {
                $('#payment_consent_alert').show();
                return;
            }

            $("#target_form").submit();
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
    getDatetime: function (amazon_order_reference_id) {

      //var elem_address = $('#OutboundAddressId');
      var elem_datetime = $('#OutboundDatetimeCd');
      $('.aircontent_select').prop('checked', false);
      $('.aircontent_text').val('');

      $('option:first', elem_datetime).prop('selected', true);
      elem_datetime.attr("disabled", "disabled");
      elem_datetime.empty();

      $.post('/Outbound/getAddressDatetimeByAmazon', {
              //address_id: elem_address.val()
              amazon_order_reference_id: amazon_order_reference_id,
              trunk_cds: JSON.parse($('#trunkCds').val())
          },

            function (data) {
              if (data.result) {
                  var optionItems = new Array();
                  $.each(data.result, function() {
                      optionItems.push(new Option(this.text, this.datetime_cd));
                  });
                  elem_datetime.append(optionItems);
                  // お届け先表示切り替え
                  $('.datetime_select').toggle(!data.isIsolateIsland);
                  $('.aircontent').hide(!data.isIsolateIsland);
                  $('.isolate_island_select').toggle(data.isIsolateIsland);
                  if (data.isIsolateIsland && !$('.aircontent_select').prop('checked')) {
                    $('#OutboundAircontentSelect1').prop('checked', true);
                    $('.datetime_select').hide();
                    $('.aircontent').show();
                  }

                  $('#isolateIsland').val(data.isIsolateIsland);
              }
          }


          ,
          'json'
      ).always(function() {
          elem_datetime.removeAttr("disabled");
      });
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
            /*
            agreementType: 'BillingAgreement',
            */
            onReady: function() {
                AppAmazonPayWallet.AmazonWidgetReadyFlag = true;
            },

            onOrderReferenceCreate: function(orderReference) {
                // Here is where you can grab the Order Reference ID.
                AppAmazonPayWallet.AmazonOrderReferenceId = orderReference.getAmazonOrderReferenceId();
            },
            // 住所選択変更時
            onAddressSelect: function () {
                // do stuff here like recalculate tax and/or shipping
                // お届希望日を取得
                AppAmazonPay.getDatetime(AppAmazonPayWallet.AmazonOrderReferenceId);
                AppAmazonUserNameDevide.a(AppAmazonPayWallet.AmazonOrderReferenceId);
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

var AppAmazonUserNameDevide =
{
  a: function(amazon_order_reference_id)
  {
    $.post('/Outbound/getAmazonUserInfoDetail', {
              amazon_order_reference_id: amazon_order_reference_id
          },

          function (data) {
            AppAmazonUserNameDevide.removeNameFormValue();

            if (data.status) {
                AppAmazonUserNameDevide.setNameFormValue(data.name.lastname, data.name.firstname);
            } else {
              //AmazonPayからの名前データ取得失敗時は入力フォーム表示
              AppAmazonUserNameDevide.showNameForm();
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

// AmazonPay callback
AppAmazonPayWallet.a();

/*
 * document ready
 * */
$(function()
{
  AppAmazonPay.a();
  AppAmazonPay.b();
  AppAmazonPay.c();
});
