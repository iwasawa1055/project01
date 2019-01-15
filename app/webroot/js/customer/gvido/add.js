var AppCustomerGvidoAdd =
{
    a: function () {
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
    b: function () {
        //ボタンの色を薄くする
        $('#execute').css("opacity", "0.5");

        $('#terms').on('click', function(){
            if ($('#terms').prop('checked')) {
                $('#execute').css("opacity", "1");

                if ($('#terms-error').length) {
                    $('#terms-error').remove();
                }
            } else {
                $('#execute').css("opacity", "0.5");
            }
        });

        $('#execute').on('click', function(){
            if ($('#terms').prop('checked')) {
                $('#CustomerRegistInfoCustomerAddForm').submit();
            } else {
                if ($('#terms-error').length == 0) {
                    $('#terms').parent('label').parent('li').append('<p class="valid-il" id="terms-error">個人情報について、利用規約をご確認下さい</p>');
                }
            }
        });
    },
    c: function () {
        // 初期表示で郵便番号が入っていれば実行する
        if($('#CustomerRegistInfoPostal').val() !== '') {
            AppCustomerGvidoAdd._get_address_datetime(true);
        }

        $('#CustomerRegistInfoPostal').change(function() {
            AppCustomerGvidoAdd._get_address_datetime(false);
        });
    },
    _get_address_datetime: function (back_flg) {
        var elem_postal = $('#CustomerRegistInfoPostal');
        var elem_datetime = $('#CustomerRegistInfoDatetimeCd');

        // 引数取得
        var params = {};
        params.postal = elem_postal.val();

        $.ajax({
            url: '/customer/gvido/as_get_address_datetime',
            cache: false,
            data: params,
            dataType: 'json',
            type: 'POST'
        }).done(function (data, textStatus, jqXHR) {
            $('#CustomerRegistInfoDatetimeCd > option').remove();
            // 成功時 お届け日時セット
            elem_datetime.append($('<option>').html('以下からお選びください').val(''));
            $.each(data.results, function (index, datatime) {
                elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
            });
            if(back_flg === true){
                elem_datetime.val(elem_datetime.data('datetime_cd'));
            }
        }).fail(function (data, textStatus, errorThrown) {
            // 失敗時 お届け日時リセット
            $('#CustomerRegistInfoDatetimeCd > option').remove();
            $('#CustomerRegistInfoDatetimeCd').append($('<option>').html('以下からお選びください').val(''));
        }).always(function (data, textStatus, returnedObject) {
            elem_datetime.removeAttr("disabled");
        });
    }
};

/*
 * document ready
 * */
$(function()
{
    AppCustomerGvidoAdd.a();
    AppCustomerGvidoAdd.b();
    AppCustomerGvidoAdd.c();
});
