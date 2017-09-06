var AppAddOrder =
{
    a: function () {
        // ボックス数選択
        $('.js-item-number').change(function () {

            // nameと同じ固有識別子をつかう
            var selector = $(this).data("box_type");

            // console.log('selector:' + selector);

            var number = Number(0);

            // セレクトボックスの値を取得
            $('.js-item-'+ selector).each(function () {
                var set_number = $(this).val();
                var selector_name = $(this).data("name");
                $('input[name='+ selector_name + ']').val(set_number);
                number += Number(set_number);
                // console.log('number:' + number);
            });

            // 表示個数を変更 select_＋nameで指定
            if (number === 0) {
                $('#select_' + selector).html('未選択');
            } else {
                $('#select_' + selector).html('<span>' +  number +'個選択済み</span>');

                // hako_limited_ver1の場合は、単位はパック
                if (selector === 'hako_limited_ver1') {
                    $('#select_' + selector).html('<span>' +  number +'パック選択済み</span>');
                }
            }
        });
    },

    b: function () {
        $('.js-btn-submit').on('click', function (e) {
            $('form').submit();
        });
    },
}

var AppAmazonPayLogin =
{
    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    a: function () {
        window.onAmazonLoginReady = function(){
            amazon.Login.setClientId(AppAmazonPayLogin.ClientId);
        };
        window.onAmazonPaymentsReady = function() {
            // Render the button here.
            AppAmazonPayLogin.e("AmazonPayButton", "/first_order/add_amazon_profile");
            AppAmazonPayLogin.e("AmazonPayButtonMono", "/first_order/add_amazon_profile");
            AppAmazonPayLogin.e("AmazonPayButtonHako", "/first_order/add_amazon_profile");
            AppAmazonPayLogin.e("AmazonPayButtonCleaning", "/first_order/add_amazon_profile");
            AppAmazonPayLogin.e("AmazonPayButtonHakoLimitedVer1", "/first_order/add_amazon_profile");
        };
    },
    b: function () {
        param = '';

        // 箱の数量を取得しパラメータを生成
        $('.js-set_num').each(function(i, elem) {

            //console.log($(this).val());
            if($(this).val() != '' && $(this).val() != '0') {
                console.log($(this).attr("name"));
                if(param != ''){
                    param += '&';
                }
                param += $(this).attr("name") + '=' +$(this).val();
            }
        });

        //console.log(str);
        return param;
    },
    d: function () {
        $('.js-amazon_pay_logout').on('click', function (e) {
            $('form').submit();
            amazon.Login.logout();
            link = $(this).data('href');

            window.location.href = link;
        });
    },
    e: function (button_name, path) {
        var authRequest;
        var host = location.protocol + '//' + location.hostname;

        OffAmazonPayments.Button(button_name, AppAmazonPayLogin.SELLER_ID, {
          type: "PwA",
          color: "Gold",
          size: "medium",
          authorization: function () {
            param = AppAmazonPayLogin.b();
            loginOptions = {scope: "profile payments:widget payments:shipping_address", popup: "true"};
            set_param='';
            if(param != ''){
              set_param = '?' + param;
            }
            authRequest = amazon.Login.authorize(loginOptions, host + path + set_param);
          },
          onError: function(error) {
          }
        });
    }
}

/*
 * document ready
 * */
$(function()
{
    AppAddOrder.a();
    AppAddOrder.b();
    AppAmazonPayLogin.a();
    AppAmazonPayLogin.d();
});
