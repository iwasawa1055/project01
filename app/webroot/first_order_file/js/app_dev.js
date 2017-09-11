// animsition
$(function() {
  Act._();
  Act.a();
  Act.b();
  Act.c();

  $('a[href^="/"]a[target!="_blank"]').addClass('animsition-link');
  $('button[type=submit]').addClass('page-transition-link');


  $('form').submit(function() {
    $('button[type=submit]', this).attr('disabled', 'true');
    if ($('button[type=submit]', this).hasClass('submit_after_restore')) {
      setTimeout(function() {
        $('button.submit_after_restore').attr('disabled', false);
      }, 1000);
    }
  });
});

var Act =
{
    // フェイドイン
    _: function()
    {
        // $('body').css({'display': 'none'}).fadeIn(1000);
    },

    // クリックワンス & ローダー出力
    a: function()
    {
        $('body').on('click', '.animsition-link, .page-transition-link', function()
        {
            if ($(this).prop('href') === 'javascript:void(0)') {
                return false;
            }

            $('.loader').airCenter();
            $('.airloader-overlay').show();
        });
    },
    b: function()
    {
        // validation メッセージが表示される時に、ページ上部に表示する
        if ($('span').hasClass('validation')) {
            $('<div class="form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.wrapper');
        }
    },
    c: function()
    {
        setTimeout(
            window.onAmazonLoginReady = function(){
                if ($('#Logout')[0]) {
                    $('#Logout').on('click', function (e) {
                        amazon.Login.logout();
                    });
                }
            }, 10);
    }

};
