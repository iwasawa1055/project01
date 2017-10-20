var AppAddAdress =
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

            $.post('/FirstOrderDirectInbound/as_getInboundDatetime', {
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
        //** Auto Kana
        $('input.lastname').airAutoKana(
        {
            dest: 'input.lastname_kana',
            katakana: true
        });

        $('input.firstname').airAutoKana(
            {
                dest: 'input.firstname_kana',
                katakana: true
            });
    },
    d: function () {
        // 通常時のバリデーションエラー時表示
        if ($('#js-display_standard').val() === 'true') {
            $('#dsn-signin').show();
        }

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
            AppAmazonPayLogin.e("AmazonPayButtonDirect","/first_order_direct_inbound/add_amazon_profile");
        };
    },
    b: function () {
        document.getElementById('Logout').onclick = function() {
            amazon.Login.logout();
        };
    },
    d: function () {
        var param = '';

        if($('[name=direct_inbound]').val() != '0') {
            param = 'direct_inbound' + '=' + $('[name=direct_inbound]').val();
        }

        return param;
    },
    e: function (button_name, path) {
        var authRequest;
        var host = location.protocol + '//' + location.hostname;
        OffAmazonPayments.Button(button_name, AppAmazonPayLogin.SELLER_ID, {
          type: "PwA",
          color: "Gold",
          size: "medium",
          authorization: function () {
            param = AppAmazonPayLogin.d();
            loginOptions = {scope: "profile payments:widget payments:shipping_address", popup: "true"};
            set_param='';
            if(param != ''){
              set_param = '?' + param;
            }
            authRequest = amazon.Login.authorize(loginOptions, host + path + set_param);
          }
        });
    }
}

/*
 * document ready
 * */
$(function()
{
    AppAddAdress.a();
    AppAddAdress.b();
    AppAddAdress.c();
    AppAddAdress.d();
    AppAmazonPayLogin.a();
    AppAmazonPayLogin.b();
});
