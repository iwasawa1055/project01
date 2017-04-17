var AppInputCredit =
{
  a: function(){

    // 預け入れ方法の選択初期化
    if ($('span').hasClass('validation')) {
      $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.dev-panel-default');
    }

  },
}


/*
 * document ready
 * */
$(function()
{
  AppInputCredit.a();
});

