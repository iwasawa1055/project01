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

/*
 * document ready
 * */
$(function()
{
    AppAddOrder.a();
    AppAddOrder.b();
});
