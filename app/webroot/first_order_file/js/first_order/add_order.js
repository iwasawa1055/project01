var AppAddOrder =
{
    a: function () {
        // スターターキットボックス選択
        $('.btn-starter').click(function () {
            if( $('.btn-starter').hasClass(("active"))) {
                $('.select-number').html('<span>1セット選択済み</span>');
                $('#select_starter_kit').val(1);
            } else {
                $('.select-number').html('未選択');
                $('#select_starter_kit').val(0);
            }
        });},
    b: function () {
        // ボックス数選択
        $('.js-item-number').change(function () {
            var selector = $(this).data("box_type");
            var number = Number(0);

            $('.js-item-'+ selector).each(function () {
                var set_number = $(this).val();
                var selector_name = $(this).data("name");
                $('input[name='+ selector_name + ']').val(set_number);
                number += Number(set_number);
                // console.log('number:' + number);
            });

            if (number === 0) {
                $('#select_' + selector).html('未選択');
            } else {
                $('#select_' + selector).html('<span>' +  number +'個選択済み</span>');
            }
        });
    },

    c: function () {
        $('.btn-submit').on('click', function (e) {
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
    AppAddOrder.c();
});
