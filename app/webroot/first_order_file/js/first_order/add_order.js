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

var AppAmazonPaymentLogin =
{
    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    a: function () {
        window.onAmazonLoginReady = function(){
            amazon.Login.setClientId(AppAmazonPaymentLogin.ClientId);
        };
        window.onAmazonPaymentsReady = function() {
            // Render the button here.
            AppAmazonPaymentLogin.c("AmazonPayButton", "/first_order/input_amazon_profile");
            AppAmazonPaymentLogin.c("AmazonPayButtonDirect", "/first_order_direct_inbound/input_amazon_profile");

        };
    },
    aa: function () {
        str = '';
        $('.js-set_num').each(function(i, elem) {

            //console.log($(this).val());
            if($(this).val() != '' && $(this).val() != '0') {
                console.log($(this).attr("name"));
                if(str != ''){
                    str += '&';
                }
                str += $(this).attr("name") + '=' +$(this).val();
            }
        });

        //console.log(str);
        return str;
    },
    b: function () {
        document.getElementById('Logout').onclick = function() {
            console.log('logout');
            amazon.Login.logout();
        };
        $('.js-amazon_pay_logout').on('click', function (e) {
            $('form').submit();
            console.log('logout');
            amazon.Login.logout();
            link = $(this).data('href');

            window.location.href = link;
    },
    c: function (button_name, path) {
        var authRequest;
        var host = location.protocol + '//' + location.hostname;

        OffAmazonPayments.Button(button_name, AppAmazonPaymentLogin.SELLER_ID, {
          type: "PwA",
          color: "Gold",
          size: "medium",
          authorization: function () {
            parem = AppAmazonPaymentLogin.aa();
            loginOptions = {scope: "profile payments:widget", popup: "true"};
            set_parem='';
            if(parem != ''){
              set_parem = '?' + parem;
            }
            authRequest = amazon.Login.authorize(loginOptions, host + path + set_parem);
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
    AppAmazonPaymentLogin.a();
    AppAmazonPaymentLogin.b();
});
