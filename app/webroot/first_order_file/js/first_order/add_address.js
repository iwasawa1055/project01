var AppAddAdress =
{
    a: function () {
        // お届け日の指定
        $('#postal').change(function() {
            getDatetime();
        });

        function getDatetime() {
            var elem_postal = $('#postal');
            var elem_datetime = $('#datetime_cd');

            $('option:first', elem_datetime).prop('selected', true);
            elem_datetime.attr("disabled", "disabled");

            // 引数取得
            var params = {};
            params.postal = elem_postal.val();

            // API実行
            if (params.postal != '') {
                $.ajax({
                    url: '/FirstOrder/as_get_address_datetime',
                    cache: false,
                    data: params,
                    dataType: 'json',
                    type: 'POST'
                }).done(function (data, textStatus, jqXHR) {
                    $('#datetime_cd > option').remove();
                    // 成功時 お届け日時セット
                    elem_datetime.append($('<option>').html('以下からお選びください').val(''));
                    $.each(data.results, function (index, datatime) {
                        elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
                    });
                    // 戻る対応でリストをpostする
                    $('#select_delivery').val(JSON.stringify(data.results));
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
    },
    b: function () {
        if ( $("#postal").val() && !$('#datetime_cd').val() ) {
            getDatetime();
            
            // format
            postal = $("#postal").val();
            postal = postal.replace(/^(\d{3})(\d{4})$/, "$1-$2");
            $("#postal").val(postal);
            
            if ( $('.address_address2').val() == "" ) {
                $('.address_pref').val('');
                $('.address_address1').val('');
                $('.address_address2').val('');
                searchAddress(postal);
            }
        }

        function getDatetime() {
            var elem_postal = $('#postal');
            var elem_datetime = $('#datetime_cd');

            // 引数取得
            var params = {};
            params.postal = elem_postal.val();

            // API実行
            if (params.postal != '') {
                $.ajax({
                    url: '/FirstOrder/as_get_address_datetime',
                    cache: false,
                    data: params,
                    dataType: 'json',
                    type: 'POST'
                }).done(function (data, textStatus, jqXHR) {
                    $('#datetime_cd > option').remove();
                    // 成功時 お届け日時セット
                    elem_datetime.append($('<option>').html('以下からお選びください').val(''));
                    $.each(data.results, function (index, datatime) {
                        elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
                    });
                    // 戻る対応でリストをpostする
                    $('#select_delivery').val(JSON.stringify(data.results));
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
