var AppInboundAttention =
{
  init: function() {
    // address情報
    AppInboundAttention.address();

    // 集荷日時を取得
    AppInboundAttention.getDatetime();

    // 入力系チェック（警告）
    AppInboundAttention.checkNameLength();
    AppInboundAttention.checkInputNameLength();

    // エラースクロール
    AppInboundAttention.scrollValidError();

    // modal表示
    if ($('.wrapping_modal').length) {
      if ( document.referrer.indexOf('/inbound/box/attention') == -1 && document.referrer.indexOf('/inbound/box/confirm') == -1) {
        $("[data-remodal-id=packaging]").remodal().open();
      }
    }
  },

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
    if($('.caution-box').length){
      // ボタンを非活性
      $('#execute').css('opacity','0.3');
      $('#execute').prop('disabled',true);
    }

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
    $('.list-slct-num input:radio').each(function(i) {
      if($(this).val() == 1){
        if ($(this).prop('checked')) {
          $('.l-select-payment').show();
          return false;
        }
      }
    });
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

  f: function () {
    // 自分で発送する際は住所情報を非表示
    $('[name="data[InboundBase][delivery_carrier]"]').change(function() {
      if ($(this).val() === '7') {
        $("#dev_collect").slideUp(300);
        $("#dev_self").slideDown(300);
      } else {
        $("#dev_collect").slideDown(300);
        $("#dev_self").slideUp(300);
      }
    });
  },

  checkInputNameLength: function () {
    $('.lastname, .firstname').blur(function () {
      AppInboundAttention.execCheckInputNameLength();
    });
  },

  execCheckInputNameLength: function () {
    var count = AppInboundAttention.strLength($('.lastname').val()+$('.firstname').val());
    if (count > 49) {
      $('.dev-name-length-error').remove();
      $('.user_name').after("<p class='valid-il dev-name-length-error'>姓名の合計が全角で25文字または半角で50文字以上の名前が設定されています。集荷時の伝票のお名前が途中で切れてしまいますので、ご変更をお願いいたします</p>");
    } else {
      $('.dev-name-length-error').remove();
    }
  },

  checkNameLength: function () {
    AppInboundAttention.execCheckNameLength();
    $('.address').on('change', function () {
      AppInboundAttention.execCheckNameLength();
    });
  },

  execCheckNameLength: function () {
    var count = AppInboundAttention.strLength($('.address :selected').data('address-name'));
    if (count > 49) {
      $('.dev-name-length-error').remove();
      $('.address').after("<p class='valid-il dev-name-length-error'>お名前が全角で25文字または半角で50文字以上入力されています。集荷時の伝票のお名前が途中で切れてしまいますので、新たにご登録をお願いいたします。</p>");
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
  },

  scrollValidError: function () {
    var img_num = $('.img-item').length;
    var img_counter = 0;
    for (var i = 0; i < img_num; i++) {
      var img = $('<img>');
      img.load(function() {
        img_counter++;
        // 全てのボックス画像を出力し終えた際に実施
        if (img_num == img_counter) {
          var valid = $(".valid-il");
          if (valid.length > 0) {
            if ($(valid).closest('div.box-info').length > 0) {
              // ボックス系のエラー
              var position = valid.parent().parent().offset().top;
            } else {
              // 入力系のエラー
              var position = valid.parent().offset().top;
            }
            $('body,html').animate({scrollTop: position}, 'slow');
          }
        }
      });
      img.attr('src', $('img').eq(i).attr('src'));
    }
  },

  scrollValidError: function () {
    var img_num = $('.img-item').length;
    var img_counter = 0;
    for (var i = 0; i < img_num; i++) {
      var img = $('<img>');
      img.load(function() {
        img_counter++;
        // 全てのボックス画像を出力し終えた際に実施
        if (img_num == img_counter) {
          var valid = $(".valid-il");
          if (valid.length > 0) {
            if ($(valid).closest('div.box-info').length > 0) {
              // ボックス系のエラー
              var position = valid.parent().parent().offset().top;
            } else {
              // 入力系のエラー
              var position = valid.parent().offset().top;
            }
            $('body,html').animate({scrollTop: position}, 'slow');
          }
        }
      });
      img.attr('src', $('img').eq(i).attr('src'));
    }
  },

  address: function() {
    // 初回表示時
    var address_id = $('#address_id').val();
    if (address_id !== 'add') {
      $('.inbound-input-address').hide();
    }
    // 選択住所変更時
    $('#address_id').change(function() {
      $('.inbound-input-address').hide();
      if ($(this).val() === 'add') {
        $('.inbound-input-address').slideDown(Speed, Ease);
      } else {
        $('.inbound-input-address').slideUp(Speed, Ease);
      }
    });
  },

  getDatetime: function () {
    var elem_address = $('#address_id');
    var elem_datetime = $('#datetime_cd');
    var week_text = ["(日)", "(月)", "(火)", "(水)", "(木)", "(金)", "(土)"];
    var select_datetime_cd = $('#select_datetime_cd').val();

    $.ajax({
      url      : '/ajax/as_getYamatoDatetime',
      cache    : false,
      dataType : 'json',
      type     : 'POST'
    }).done(function (data, textStatus, jqXHR) {
      elem_datetime.empty();
      $.each(data.results, function (index, datatime) {
        var date_obj = new Date(index);
        var week = date_obj.getDay();
        $.each(datatime, function (datatime_key, datatime_text) {
          var time_text = index.replace(/-/g, '/') + ' ' + week_text[week] + ' ' + datatime_text;
          var time_cd   = index + '-' + datatime_key;
          elem_datetime.append($('<option>').html(time_text).val(time_cd));
        });
      });
    }).fail(function (data, textStatus, errorThrown) {
    }).always(function (data, textStatus, returnedObject) {
      elem_datetime.removeAttr("disabled");
      // 選択
      if (select_datetime_cd.length) {
        elem_datetime.val(select_datetime_cd);
      } else {
        elem_datetime.prop("selectedIndex", 0);
      }
    });
  },
}

/*
 * document ready
 * */
$(function()
{
  AppInboundAttention.init();
  AppInboundAttention.a();
  AppInboundAttention.b();
  AppInboundAttention.c();
  AppInboundAttention.d();
  AppInboundAttention.e();
  AppInboundAttention.f();
});

// モーダルでエラーが発生するので打ち消し
function scrollTo(_target,_correction,_speed) {}

