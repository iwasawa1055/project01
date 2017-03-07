var AppConfirm =
{
    a: function () {
        // 確認画面でエラーとなった場合、購入フローでエラーとなっているので、お問い合わせを表示する
        if ($('div').hasClass('alert-danger')) {
            $('div.alert-danger').empty();
            $('div.alert-danger').prepend('<i class="fa fa-exclamation-triangle"></i> 購入を完了することができませんでした。お手数ですがお問い合わせください。<br><a href="/inquiry/add" target="_blank">お問い合わせページ</a>')
        }
    },
    b: function () {
        // 価格取得APIでエラーの場合
        console.log("kita");
        if ($('#price_table span').hasClass('validation')) {
            console.log("kita2");
            $('div.alert-danger').empty();
            $('div.alert-danger').prepend('<i class="fa fa-exclamation-triangle"></i> 料金情報を取得することができませんでした。<br><a href="/inquiry/add" target="_blank">お問い合わせページ</a>')
        }
    },

}

/*
 * document ready
 * */
$(function()
{
    AppConfirm.a();
    AppConfirm.b();
});
