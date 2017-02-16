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
                    console.log('done');
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
                    console.log('fail');
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

}

/*
 * document ready
 * */
$(function()
{
    AppAddAdress.a();
});
