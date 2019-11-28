var pickup_date_time;

var AppAddAdress =
{
    DELIVERY_ID_PICKUP : '6',
    DELIVERY_ID_MANUAL : '7',
    a: function(){
        var week_text = ["(日)", "(月)", "(火)", "(水)", "(木)", "(金)", "(土)"];

        $.ajax({
          url: '/ajax/as_getYamatoDatetime',
          cache: false,
          dataType: 'json',
          type: 'POST'
        }).done(function (data, textStatus, jqXHR) {
            if (data.results) {
                pickup_date_time = data.results;
                $('#InboundDayCd option').remove();
                $('#InboundTimeCd option').remove();

                var pickup_date = null;
                // 集荷日をセット
                for (var item in pickup_date_time) {
                    // 最初の日付での時間を下でセットする
                    if (pickup_date == null) {
                        pickup_date = item;
                    }

                    // 集荷日程をセット
                    var date_obj = new Date(item);
                    var week = date_obj.getDay();
                    var pickup_date_text = item.replace(/-/g, '/') + ' ' + week_text[week]; 

                    $('#InboundDayCd').append($('<option>').text(pickup_date_text).attr('value', item));
                }

                // 戻るボタンで戻ってきた時は選択していた日付をselectedする
                if ($('#select_delivery_day').val() != '') {
                   $('#InboundDayCd').val($('#select_delivery_day').val());
                    pickup_date = $('#select_delivery_day').val();
                }

                // 時間をセット
                for(var item in pickup_date_time[pickup_date]) {
                    var pickup_time_text = pickup_date_time[pickup_date][item];
                    $('#InboundTimeCd').append($('<option>').text(pickup_time_text).attr('value', item));
                }

                // 戻るボタンで戻ってきた時は選択していた時間付をselectedする
                if ($('#select_delivery_time').val() != '') {
                   $('#InboundTimeCd').val($('#select_delivery_time').val());
                }
            }

        }).fail(function (data, textStatus, errorThrown) {
            console.log(data.results);
            $('#InboundDayCd').removeAttr("disabled");
            $('#InboundTimeCd').removeAttr("disabled");
        }).always(function (data, textStatus, returnedObject) {
            console.log(data.results);
            $('#InboundDayCd').removeAttr("disabled");
            $('#InboundTimeCd').removeAttr("disabled");
        });
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
    e: function() {
        // 日付selectboxで変更した時
        $('#InboundDayCd').change(function() {
            var change_pickup_date = $('#InboundDayCd option:selected').val();
            $('#InboundTimeCd option').remove();
            for(var item in pickup_date_time[change_pickup_date]) {
                var pickup_time_text = pickup_date_time[change_pickup_date][item];
                $('#InboundTimeCd').append($('<option>').text(pickup_time_text).attr('value', item));
            }
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
    PickupYamato.getDateTime();
    PickupYamato.changeSelectPickup();
    //AppAddAdress.a();
    AppAddAdress.b();
    AppAddAdress.c();
    AppAddAdress.d();
    AppAddAdress.e();
});

// widjet.jsのコールバックの設定なので、即時実行しておく
AppAmazonPayLogin.a();
AppAmazonPayLogin.b();
