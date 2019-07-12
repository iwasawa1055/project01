var AppInboundAttention =
{
  a: function()
  {
    $('.execute').on('click', function (e) {
      var is_update = $('#is_update').val();
      // カード更新
      if (is_update === '1') {
          gmoCreditCardPayment.setGMOTokenAndUpdateCreditCard();
      // カード登録
      } else {
          gmoCreditCardPayment.setGMOTokenAndRegisterCreditCard();
      }

    });
  },
  b: function()
  {
      var sc = $('#input-exist'); // 既存
      var cc = $('#input-change'); // 変更
      var nc = $('#input-new'); // 新規

      var is_update = $('#is_update').val();
      // カード更新
      if (is_update === '1') {
          var form_text = $('form').attr('id');
          form_text = form_text.replace("InputCardForm", "");
          var card_radio = $('input[name="data['+ form_text +'][select-card]"]:checked').val();
          // 既存チェック時
          if (card_radio == 'as-card') {
            cc.hide();
            nc.hide();
          // 変更チェック時
          } else {
            sc.hide();
            nc.hide();
          }
      // カード登録
      } else {
          cc.hide();
          sc.hide();
          $('.input-check-list').hide();
      }

      $('.card_check_type').change(function() {
          if ($(this).val() === 'as-card') {
              sc.slideDown(Speed, Ease);
              cc.slideUp(Speed, Ease);
              nc.slideUp(Speed, Ease);
          }
          if ($(this).val() === 'change-card') {
              cc.slideDown(Speed, Ease);
              sc.slideUp(Speed, Ease);
              nc.slideUp(Speed, Ease);
          }
      });
  },
  c: function ()
  {
    // ボタンを非活性
    $('#execute').css('opacity','0.3');
    $('#execute').prop('disabled',true);

    $('.caution-box input').change(function () {
      if ($(this).prop('checked')) {
        var exe_flag = true;
        $(".caution-box input").each(function(i) {
          if ($(this).prop("checked") == false) {
            exe_flag = false;
            return false;
          }
        });
        if (exe_flag) {
          $('#execute').css("opacity", "1");
          $('#execute').prop('disabled',false);
        }
      } else {
        $('#execute').css("opacity", "0.5");
        $('#execute').prop('disabled',false);
      }
    });
  },
  d: function ()
  {
    $('#execute').on('mouseup', function(){
      var exe_flag = true;
      $(".caution-box input").each(function(i) {
        if (!$(this).prop("checked")) {
          exe_flag = false;
          return false;
        }
      });
      if (exe_flag) {
        $('.loader').airCenter();
        $('.airloader-overlay').show();
        $(this).closest("form").submit();
      }
    });
  },

  e: function () {
    // カードエリア出力
    $('.l-select-payment').hide();
    $('.list-slct-num input:radio').change(function () {
      var card_flag = false;
      $('.list-slct-num input:radio').each(function(i) {
        if($(this).val() == 1){
          if ($(this).prop('checked')) {
            card_flag = true;
            return false;
          }
        }
      });

      if (card_flag) {
        $('.l-select-payment').show('slow');
      } else {
        $('.l-select-payment').hide('slow');
      }
    });
    return false;
  },
}

/*
 * document ready
 * */
$(function()
{
  AppInboundAttention.a();
  AppInboundAttention.b();
  AppInboundAttention.c();
  AppInboundAttention.d();
  AppInboundAttention.e();
});

