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

    b: function()
    {
        // validation メッセージが表示される時に、ページ上部に表示する
        if ($('span').hasClass('validation')) {
            $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.dev-wrapper');
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
}

/*
 * document ready
 * */
$(function()
{
    AppAddAdress.a();
    AppAddAdress.b();
    AppAddAdress.c();
});
