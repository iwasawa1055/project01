$(function () {
    // 起動時の初期処理
    AppInit.init_a();
    AppCleaning.a();
    AppCleaning.b();
    AppCleaning.c();
    AppCleaning.d();
});

var AppCleaning = {
    a : function() {
        // 検索ボタンが押された時の処理
        $('#search').on('click', function(){

            var self = $(this);
            var add_billing  = $('<input type="hidden" name="data[Cleaning][search]" value="1">');
            add_billing.insertAfter(self);

            $("#itemlist").submit();
        });
    },
    b : function() {
        // 確認ボタンが押された場合の処理
        $('#execute').on('click', function(){
            $("#itemlist").submit();
        });
    },
    c : function() {
        // 確認ボタンが押された場合の処理
        $('#check_all').on('click', function(){
            // 合計値
            var all_num = 0;
            var all_price = 0;

            // チェック状態を取得
            var check_flag = false;
            if ($(this).prop('checked')) {
                check_flag = true;
            }
            // 各アイテムへチェック処理
            $(".check_item").each(function(i) {
                $(this).prop("checked", check_flag);

                // check on
                if (check_flag) {
                    all_num += 1;
                    all_price += parseInt($(this).parents('.item_list').find('.l-item-desc p').text().replace(",", ""));
                }
            });

            // カンマ区切り
            if (all_price >= 1000) {
                all_price = String(all_price).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
            }

            $("#all_num").text(all_num);
            $("#all_price").text(all_price);
        });
    },
    d : function() {
        // 確認ボタンが押された場合の処理
        $('.check_item').on('click', function(){
            // チェック状態を取得
            var check_flag = false;
            if ($(this).prop('checked')) {
                check_flag = true;
            }

            // 合計値
            var all_num = parseInt($("#all_num").text());
            var all_price = $("#all_price").text();
            // 既存値からカラムを外す
            all_price = parseInt(String(all_price).replace(",", ""));
            // 対象からカラムを外した値を加算/減算
            if(check_flag) {
                all_price += parseInt($(this).parents('.item_list').find('.l-item-desc p').text().replace(",", ""));
                all_num += 1;
            } else {
                all_price -= parseInt($(this).parents('.item_list').find('.l-item-desc p').text().replace(",", ""));
                all_num -= 1;
            }

            // カンマ区切り
            if (all_price >= 1000) {
                all_price = String(all_price).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
            }

            $("#all_num").text(all_num);
            $("#all_price").text(all_price);
        });
    },
}

var AppInit = {
    init_a : function() {
        // 画面表示時の初期算出
        var all_num = parseInt($("#all_num").text());
        var all_price = $("#all_price").text();
        all_price = parseInt(String(all_price).replace(",", ""));
        $(".check_item").each(function (i) {
            if ($(this).prop('checked')) {
                all_num += 1;
                all_price += parseInt($(this).parents('.item_list').find('.l-item-desc p').text().replace(",", ""));
            }
        });
        if (all_price >= 1000) {
            all_price = String(all_price).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        }
        $("#all_num").text(all_num);
        $("#all_price").text(all_price);
    },
};
