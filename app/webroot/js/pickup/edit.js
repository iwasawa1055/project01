var AppPickupEdit =
{
  init: function() {
    AppPickupEdit.checkNameLength();
    AppPickupEdit.checkInputNameLength();
  },
  checkInputNameLength: function () {
      $('.lastname, .firstname').blur(function () {
          AppPickupEdit.execCheckInputNameLength();
      });
  },
  execCheckInputNameLength: function () {
      var count = AppPickupEdit.strLength($('.lastname').val()+$('.firstname').val());
      if (count > 49) {
          $('.dev-name-length-error').remove();
          $('.firstname').after("<p class='error-message dev-name-length-error'>姓名の合計が全角で25文字または半角で50文字以上の名前が設定されています。集荷時の伝票のお名前が途中で切れてしまいますので、ご変更をお願いいたします。</p>");
      } else {
          $('.dev-name-length-error').remove();
      }
  },
  checkNameLength: function () {
      AppPickupEdit.execCheckNameLength();
      $('.address').on('change', function () {
          AppPickupEdit.execCheckNameLength();
      });
  },
  execCheckNameLength: function () {
      var count = AppPickupEdit.strLength($('[name="data[PickupYamato][address_id]"] :selected').data('address-name'));
      if (count > 49) {
          $('.dev-name-length-error').remove();
          $('.address').after("<p class='error-message dev-name-length-error'>お名前が全角で25文字または半角で50文字以上入力されています。集荷時の伝票のお名前が途中で切れてしまいますので、新たにご登録をお願いいたします。</p>");
      } else {
          $('.dev-name-length-error').remove();
      }
  },
  strLength: function(str, encode) {
    var count     = 0,
        setEncode = 'Shift_JIS',
        c         = '';

    if (encode && encode !== '') {
        if (encode.match(/^(SJIS|Shift[_\-]JIS)$/i)) {
            setEncode = 'Shift_JIS';
        } else if (encode.match(/^(UTF-?8)$/i)) {
            setEncode = 'UTF-8';
        }
    }

    for (var i = 0, len = str.length; i < len; i++) {
        c = str.charCodeAt(i);
        if (setEncode === 'UTF-8') {
            if ((c >= 0x0 && c < 0x81) || (c == 0xf8f0) || (c >= 0xff61 && c < 0xffa0) || (c >= 0xf8f1 && c < 0xf8f4)) {
                count += 1;
            } else {
                count += 2;
            }
        } else if (setEncode === 'Shift_JIS') {
            if ((c >= 0x0 && c < 0x81) || (c == 0xa0) || (c >= 0xa1 && c < 0xdf) || (c >= 0xfd && c < 0xff)) {
                count += 1;
            } else {
                count += 2;
            }
        }
    }
    return count;
  }
}


/*
 * document ready
 * */
$(function()
{
  AppPickupEdit.init();
});
