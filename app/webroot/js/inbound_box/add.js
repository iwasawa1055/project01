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
      .then(AppInboundBoxAdd.autoKana);

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
    $("[data-remodal-id=packaging]").remodal().open();
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
      });
    } else {
        $('#dev-new-box-grid').after("<p class='page-caption not-applicable'><br><br>新しく購入したボックスが存在しません。<br><br><br>");
    }

    if (AppInboundBoxAdd.old_box.length > 0) {
      $.each(AppInboundBoxAdd.old_box, function(index, value){
        var renderOldBox = AppInboundBoxAdd.createHtml(value);
        $('#dev-old-box-grid').append(renderOldBox);
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
            $('[name="data[Inbound][box_list]['+index+']['+i1+']"').after('<p class="valid-il">'+v2+'</p>');
          });
        });
      });
    }
    // selected
    if (typeof $("#dev-box-list-selected").val() !== 'undefined') {
      var boxListSelected = JSON.parse($("#dev-box-list-selected").val());
      $.each(boxListSelected, function(index, value){
        if (value.checkbox == "1") {
          $('[name="data[Inbound][box_list]['+index+'][checkbox]"').prop('checked', true);
        }
        $('[name="data[Inbound][box_list]['+index+'][title]"').val(value.title);
        if (typeof value.wrapping_type !== 'undefined' && value.wrapping_type == 1) {
          $('[name="data[Inbound][box_list]['+index+'][wrapping_type]"').prop('checked', true);
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
    html += '        <p class="box-id">'+value.box_id+'</p>';
    html += '        <p class="box-type">'+value.kit_name+'</p>';
    html += '        <input type="text" name="data[Inbound][box_list]['+value.box_id+'][title]" placeholder="ボックス名を記入してください" class="box-input-name">';

    if (value.kit_cd == '66' || value.kit_cd == '67' || value.kit_cd == '82') {
      html += '        <input type="hidden" name="data[Inbound][box_list]['+value.box_id+'][wrapping_type]" class="cb-circle dev-box-check" value="0">';
      html += '        <label class="input-check">';
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
