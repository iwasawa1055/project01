
var AppAmazonPay =
{
    DELIVERY_ID_PICKUP : '6',
    DELIVERY_ID_MANUAL : '7',

    a: function () {
        $('.js-btn-submit').on('click', function (e) {
            var self = $(this);
            var add_billing  = $('<input type="hidden" name="amazon_order_reference_id">');
            add_billing.val(AppAmazonPayWallet.AmazonOrderReferenceId);
            add_billing.insertAfter(self);

            // サブミット前チェック確認
            // 定期購入未チェックでエラー
            if(AppAmazonPayWallet.buyerBillingAgreementConsentStatus == 'false') {
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
}

var AppAmazonPayWallet =
{

    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    AmazonOrderReferenceId: '',
    buyerBillingAgreementConsentStatus: false,
    AmazonWidgetReadyFlag: false,

    a: function () {

        console.log("AppAmazonPayWallet : a");


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

                //************* test log *************
                if ($('#addressBookWidgetDiv')[0]) {
                    //console.log("addressBookWidgetDiv is found.");
                } else {
                    console.log("addressBookWidgetDiv is NOT found.");
                }
                //************* test log *************

                AppAmazonPayWallet.AmazonOrderReferenceId = billingAgreement.getAmazonOrderReferenceId();
            },
            // 住所選択変更時
            onAddressSelect: function () {
                // do stuff here like recalculate tax and/or shipping
                // お届希望日を取得
                //AppAmazonPay.ajax_dateime(AppAmazonPayWallet.AmazonOrderReferenceId);

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
    },
    c : function () {
        if (AppAmazonPayWallet.AmazonWidgetReadyFlag === false) {
            AppAmazonPayWallet.b();
        }
    },
    d : function () {
        // 5秒後にwidget表示の処理が実行されていない場合は、再実行
        setTimeout(function(){AppAmazonPayWallet.c()}, 5000);
    }
}

var AppAmazonUserNameDevide =
{
  a: function(amazon_order_reference_id)
  {
    $.post('/InboundBox/getAmazonUserInfoDetail', {
              amazon_pay_data: {amazon_order_reference_id}
          },

          function (data) {

            AppAmazonUserNameDevide.removeNameFormValue();

            if (data.status) {

                var user_name = data.address.name;
                var split_name;

                /* 空白処理 */
                //文字列の前後にある空白を削除
                user_name = $.trim(user_name);
                //空白を全て全角へ
                user_name = user_name.replace(/ /g,"　");
                //連続した空白を1文字へ
                user_name = user_name.replace(/　+/g,"　");


                //名前が1文字のとき
                if (user_name.length === 1)
                {
                  AppAmazonUserNameDevide.showNameForm();
                  return;
                }

                //名前が空白１個のとき
                if ((user_name.split('　').length - 1) === 1)
                {
                  split_name = user_name.split('　');
                  if (split_name[0].length <= 29 && split_name[1].length <= 29)
                  {
                    AppAmazonUserNameDevide.setNameFormValue(split_name[0], split_name[1]);
                    return;
                  } 
                }

                split_name = [];
                //名前が空白なし or 空白２個以上 or 空白１つだけど分割すると文字数オーバー のとき 
                if (user_name.length <= 29)
                {
                    //29文字以下のとき
                    split_name[0] = user_name.substr(0, user_name.length - 1);
                    split_name[1] = user_name.substr(-1);
                }
                else if (user_name.length <= 58)
                {
                    //最大文字数超えてないとき
                    split_name[0] = user_name.substr(0, 29);
                    split_name[1] = user_name.substr(29);
                } else {
                    //分割できないので入力フォーム出す
                    AppAmazonUserNameDevide.showNameForm();
                    return;
                }
                AppAmazonUserNameDevide.setNameFormValue(split_name[0], split_name[1]);
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

/*
 * document ready
 * */
$(function()
{
  AppAmazonPay.a();
  AppAmazonPay.b();
  AppAmazonPay.c();
  AppAmazonPayWallet.a();
  AppAmazonPayWallet.d();
});
