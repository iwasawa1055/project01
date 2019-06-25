var AppInboundBoxAdd =
{
  new_box: [],
  display_new_box: [],
  old_box: [],
  display_old_box: [],
  init: function() {
    $.when(AppInboundBoxAdd.getAllNewBox(), AppInboundBoxAdd.getAllOldBox())
      .then(AppInboundBoxAdd.render)
      .then(AppInboundBoxAdd.submitForm)
      .then(AppInboundBoxAdd.address)
      .then(AppInboundBoxAdd.autoKana)
      .then(AppInboundBoxAdd.checkNameLength)
      .then(AppInboundBoxAdd.checkInputNameLength)
      .then(AppInboundBoxAdd.scrollValidError);

    // 初期表示
    if (typeof $("#dev-selected-box_type").val() !== 'undefined') {
      var box_type = $("#dev-selected-box_type").val();
      if (box_type == "new") {
        $('[name="data[Inbound][box_type]"]:eq(0)').prop('checked', true);
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev-input-box-type-new").show();
      } else {
        $('[name="data[Inbound][box_type]"]:eq(1)').prop('checked', true);
        $("input[name='data[Inbound][delivery_carrier]']:eq(0)").prop('checked', true);
        $("#dev-self-delivery").hide();
        $("#dev-input-box-type-new").show();
        $('#dev-new-box').fadeOut(400, 'easeInOutExpo');
        $('#dev-old-box').fadeIn(400, 'easeInOutExpo');
      }
    } else {
        $('[name="data[Inbound][box_type]"]:eq(0)').prop('checked', true);
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev-input-box-type-new").show();
    }

    $('[name="data[Inbound][box_type]"]').change(function() {
      if ($('.dev-box-check:checked').length > 0) {
        var ret = window.confirm('現在ご選択中のボックスがクリアされます。よろしいですか？');
        if (ret == true) {
          $('.dev-box-check:checked').prop('checked', false);
        } else {
          if ($('[name="data[Inbound][box_type]"]:eq(0)').prop('checked')) {
            $('[name="data[Inbound][box_type]"]:eq(1)').prop('checked', true);
          } else {
            $('[name="data[Inbound][box_type]"]:eq(0)').prop('checked', true);
          }
        }
      }

      if ($(this).val() === 'new') {
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev-input-box-type-new").show();
      } else if ($(this).val() === 'old') {
        $("input[name='data[Inbound][delivery_carrier]']:eq(0)").prop('checked', true);
        $("#dev-self-delivery").hide();
        $("#dev-input-box-type-new").show();
        $('#dev-new-box').fadeOut(400, 'easeInOutExpo');
        $('#dev-old-box').fadeIn(400, 'easeInOutExpo');
      }
    });

    // 初期表示
    if ($('[name="data[Inbound][delivery_carrier]"]:checked').val() === '7') {
        $("#dev-input-box-type-new").hide();
    } else {
        $("#dev-input-box-type-new").show();
    }

    $('[name="data[Inbound][delivery_carrier]"]').change(function() {
      if ($(this).val() === '7') {
        $("#dev-input-box-type-new").hide();
      } else {
        $("#dev-input-box-type-new").show();
      }
    });

    var address_id = $('[name="data[Inbound][address_id]"]').val();
    if (address_id == 'add') {
      $('.input-address').show();
    }

    // modal表示
    if ( document.referrer.indexOf('/inbound/box/add') == -1 && document.referrer.indexOf('/inbound/box/confirm') == -1) {
      $("[data-remodal-id=packaging]").remodal().open();
    }
  },
  getAllNewBox: function() {
    var d = new $.Deferred();
    $.ajax({
      type: "GET",
      url: "/inbound/box/as_get_new_box",
    })
      .then(
        // 通信成功
        function(jsonResponse){
          AppInboundBoxAdd.new_box = JSON.parse(jsonResponse);
          d.resolve();
        },
        // 通信失敗
        function(){
          d.reject();
        }
      );
    return d.promise();
  },
  getAllOldBox: function() {
    var d = new $.Deferred();
    $.ajax({
      type: "GET",
      url: "/inbound/box/as_get_old_box",
    })
      .then(
        // 通信成功
        function(jsonResponse){
          AppInboundBoxAdd.old_box = JSON.parse(jsonResponse);
          d.resolve();
        },
        // 通信失敗
        function(){
          d.reject();
        }
      );
    return d.promise();
  },
  render: function() {
    if (AppInboundBoxAdd.new_box.length > 0) {
      $.each(AppInboundBoxAdd.new_box, function(index, value){
        var renderNewBox = AppInboundBoxAdd.createHtml(value);
        $('#dev-new-box-grid').append(renderNewBox);

        // bind event
        $('.box-input-name ,[name=remove-package]').prop('disabled', true);
        $('.remove-package').addClass('input-disabled');

        $('[name="data[Inbound][box_list]['+value.box_id+'][checkbox]"]').change(function() {
            if ($(this).prop('checked')) {
                $(this).parent().next().children('.box-input-name').addClass('item-checked').prop('disabled', false);
                $(this).parent().next().children('.remove-package').removeClass('input-disabled').children('[name=remove-package]').prop('disabled', false);
            } else {
                $(this).parent().next().children('.box-input-name').removeClass('item-checked').prop('disabled', true);
                $(this).parent().next().children('.remove-package').addClass('input-disabled').children('[name=remove-package]').prop('disabled', true);
            }
        });
      });
    } else {
        $('#dev-new-box-grid').after("<p class='page-caption not-applicable'><br><br>新しいボックスが存在しません。<br><br><br>");
    }

    if (AppInboundBoxAdd.old_box.length > 0) {
      $.each(AppInboundBoxAdd.old_box, function(index, value){
        var renderOldBox = AppInboundBoxAdd.createHtml(value);
        $('#dev-old-box-grid').append(renderOldBox);

        // bind event
        $('.box-input-name ,[name=remove-package]').prop('disabled', true);
        $('.remove-package').addClass('input-disabled');

        $('[name="data[Inbound][box_list]['+value.box_id+'][checkbox]"]').change(function() {
            if ($(this).prop('checked')) {
                $(this).parent().next().children('.box-input-name').addClass('item-checked').prop('disabled', false);
                $(this).parent().next().children('.remove-package').removeClass('input-disabled').children('[name=remove-package]').prop('disabled', false);
            } else {
                $(this).parent().next().children('.box-input-name').removeClass('item-checked').prop('disabled', true);
                $(this).parent().next().children('.remove-package').addClass('input-disabled').children('[name=remove-package]').prop('disabled', true);
            }
        });
      });
    } else {
        $('#dev-old-box-grid').after("<p class='page-caption not-applicable'><br><br>取り出し済ボックスが存在しません。<br><br><br>");
    }

    // error
    if (typeof $("#dev-box-list-errors").val() !== 'undefined') {
      var boxListErrors = JSON.parse($("#dev-box-list-errors").val());
      $.each(boxListErrors, function(index, value){
        $.each(value, function(i1, v1){
          $.each(v1, function(i2, v2){
            $('[name="data[Inbound][box_list]['+index+']['+i1+']"]').after('<p class="valid-il">'+v2+'</p>');
          });
        });
      });
    }

    // selected
    if (typeof $("#dev-box-list-selected").val() !== 'undefined') {
      var boxListSelected = JSON.parse($("#dev-box-list-selected").val());
      $.each(boxListSelected, function(index, value){
        if (value.checkbox == "1") {
          $('[name="data[Inbound][box_list]['+index+'][checkbox]"]').prop('checked', true);
          $('[name="data[Inbound][box_list]['+index+'][checkbox]"]').parent().next().children('.box-input-name').addClass('item-checked').prop('disabled', false);
          $('[name="data[Inbound][box_list]['+index+'][checkbox]"]').parent().next().children('.remove-package').removeClass('input-disabled').children('[name=remove-package]').prop('disabled', false);
        }
        $('[name="data[Inbound][box_list]['+index+'][title]"]').val(value.title);
        if (typeof value.wrapping_type !== 'undefined' && value.wrapping_type == 1) {
          $('[name="data[Inbound][box_list]['+index+'][wrapping_type]"]').prop('checked', true);
        }
      });
    }

    return new $.Deferred().resolve().promise();
  },
  createHtml: function(value) {
    var html = '';
    html += '<li>';
    html += '    <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][box_id]" value="'+value.box_id+'" id="InboundBoxList'+value.box_id+'BoxId">';
    html += '    <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][product_cd]" value="'+value.product_cd+'" id="InboundBoxList'+value.box_id+'ProductCd">';
    html += '    <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][product_name]" value="'+value.product_name+'" id="InboundBoxList'+value.box_id+'ProductName">';
    html += '    <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][kit_cd]" value="'+value.kit_cd+'" id="InboundBoxList'+value.box_id+'KitCd">';
    html += '    <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][checkbox]" class="cb-circle dev-box-check" value="0">';
    html += '    <label class="input-check">';
    html += '        <input type="checkbox" name="data[Inbound][box_list]['+value.box_id+'][checkbox]" class="cb-circle dev-box-check" value="1"><span class="icon"></span>';
    html += '        <span class="item-img"><img src="'+AppInboundBoxAdd.getProductImage(value.kit_cd)+'" alt="'+value.kit_name+'" class="img-item"></span>';
    html += '    </label>';
    html += '    <div class="box-info">';
    html += '        <p class="l-box-id">';
    html += '            <span class="txt-box-id">'+value.box_id+'</span>';
    if (value.free_limit_date) {
      html += '            <span class="txt-free-limit">無料期限<span class="date">' + value.free_limit_date + '</span></span>';
    }
    html += '        </p>';
    html += '        <p class="box-type">'+value.kit_name+'</p>';
    html += '        <input type="text" name="data[Inbound][box_list]['+value.box_id+'][title]" placeholder="ボックス名を記入してください" class="box-input-name">';

    if (value.kit_cd == '66' || value.kit_cd == '67' || value.kit_cd == '82') {
      html += '        <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][wrapping_type]" class="cb-circle dev-box-check" value="0">';
      html += '        <label class="input-check remove-package">';
      html += '            <input type="checkbox" name="data[Inbound][box_list]['+value.box_id+'][wrapping_type]" class="cb-square dev-box-check" value="1">';
      html += '            <span class="icon"></span>';
      html += '            <span class="label-txt">外装を除いて撮影</span>';
      html += '        </label>';
    }

    html += '    </div>';
    html += '</li>';
    return html;
  },
  submitForm: function() {
    $("#execute").click("on", function(){
      var error = 0;
      var box_type = $('[name="data[Inbound][box_type]"]:checked').val();
      if (typeof box_type === 'undefined' || box_type == "new") {
        $('#dev-new-box-grid > li').each(function(i1, v1){
          // チェックが付いている
          if ($(v1).children(".input-check").children('.cb-circle:checked').val() == 1) {
            if ($(v1).children(".box-info").children('.box-input-name').val() == '') {
              error = 1;
            }
          }
        });
      } else {
        $('#dev-old-box-grid > li').each(function(i1, v1){
          // チェックが付いている
          if ($(v1).children(".input-check").children('.cb-circle:checked').val() == 1) {
            if ($(v1).children(".box-info").children('.box-input-name').val() == '') {
              error = 1;
            }
          }
        });
      }

      // 選択済みでボックスタイトルが設定されていない
      if (error === 1) {
        alert('選択されたボックスにボックス名が入力されていません。');
        return false;
      }

      document.form.submit();
    });
    return new $.Deferred().resolve().promise();
  },
  getProductImage: function(kit_cd) {
    switch (kit_cd){
      // HAKO レギュラー
      case '64':
        return '/images/hako-regular.png';
      // HAKO アパレル
      case '65':
        return '/images/hako-apparel.png';
      // HAKO ブック
      case '81':
        return '/images/hako-book.png';

      // MONO レギュラー
      case '66':
        return '/images/mono-regular.png';
      // MONO アパレル
      case '67':
        return '/images/mono-apparel.png';
      // MONO ブック
      case '82':
        return '/images/mono-regular.png';

      // クリーニングパック
      case '75':
        return '/images/cleaning.png';

      // Library
      case '214':
      case '215':
        return '/images/library.png';

      // closet
      case '216':
        return '/images/cleaning.png';
    }
  },
  address: function() {
    $('[name="data[Inbound][address_id]"]').change(function() {
        $('.input-address').hide();
        if ($(this).val() === 'add') {
            $('.input-address').toggle('fast');
        } else {
            $('.input-address').hide();
        }
    });
    return false;
  },
  autoKana: function () {
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
  checkInputNameLength: function () {
      $('.lastname, .firstname').blur(function () {
          AppInboundBoxAdd.execCheckInputNameLength();
      });
  },
  execCheckInputNameLength: function () {
      var count = AppInboundBoxAdd.strLength($('.lastname').val()+$('.firstname').val());
      if (count > 49) {
          $('.dev-name-length-error').remove();
          $('.firstname').after("<p class='valid-il dev-name-length-error'>姓名の合計が全角で25文字または半角で50文字以上の名前が設定されています。集荷時の伝票のお名前が途中で切れてしまいますので、ご変更をお願いいたします</p>");
      } else {
          $('.dev-name-length-error').remove();
      }
  },
  checkNameLength: function () {
      AppInboundBoxAdd.execCheckNameLength();
      $('.address').on('change', function () {
          AppInboundBoxAdd.execCheckNameLength();
      });
  },
  execCheckNameLength: function () {
      var count = AppInboundBoxAdd.strLength($('.address :selected').data('address-name'));
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
  }
}

/*
 * document ready
 * */
$(function()
{
  AppInboundBoxAdd.init();

  //集荷日を選択時に集荷時間をセットする
  PickupYamato.changeSelectPickup();

  // 集荷日と集荷時間取得
  PickupYamato.getDateTime();
});

// モーダルでエラーが発生するので打ち消し
function scrollTo(_target,_correction,_speed) {}
