var AppInboundBaseBoxAdd =
{
  init: function() {
    // ボックスタイプ別に出力する内容を切り替える
    $('[name="data[InboundBase][box_type]"]').change(function() {
      if ($(this).val() === 'new') {
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev-input-box-type-new").show();
      } else if ($(this).val() === 'old') {
        $("input[name='data[InboundBase][delivery_carrier]']:eq(0)").prop('checked', true);
        $("#dev-self-delivery").hide();
        $("#dev-input-box-type-new").show();
        $('#dev-new-box').fadeOut(400, 'easeInOutExpo');
        $('#dev-old-box').fadeIn(400, 'easeInOutExpo');
      }
    });

    // 自分で発送する際は住所情報を非表示
    $('[name="data[InboundBase][delivery_carrier]"]').change(function() {
      if ($(this).val() === '7') {
        $("#dev-input-box-type-new").hide();
      } else {
        $("#dev-input-box-type-new").show();
      }
    });

    // modal表示
    // $("[data-remodal-id=packaging]").remodal().open();
  },

  checkSelectBox: function() {
    $('.dev-box-check').each(function(index) {
      if ($(this).prop("checked") == true) {
        $(this).parents('li').find('.box-input-name').addClass("item-checked");
        $(this).parents('li').find('.box-input-name').prop("disabled", false);
      } else {
        $(this).parents('li').find('.box-input-name').removeClass("item-checked");
        $(this).parents('li').find('.box-input-name').prop("disabled", true);
      }
    });

    $('.input-check').on('click', function (e) {
      if ($(this).find('.dev-box-check').prop("checked") == true) {
        $(this).parent().find('.box-input-name').addClass("item-checked");
        $(this).parent().find('.box-input-name').prop("disabled", false);
      } else {
        $(this).parent().find('.box-input-name').removeClass("item-checked");
        $(this).parent().find('.box-input-name').prop("disabled", true);
      }
    });
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
}

/*
 * document ready
 * */
$(function()
{
  AppInboundBaseBoxAdd.init();
  AppInboundBaseBoxAdd.checkSelectBox();
  AppInboundBaseBoxAdd.submitForm();
  AppInboundBaseBoxAdd.address();

  //集荷日を選択時に集荷時間をセットする
  PickupYamato.changeSelectPickup();

  // 集荷日と集荷時間取得
  PickupYamato.getDateTime();
});

// モーダルでエラーが発生するので打ち消し
// function scrollTo(_target,_correction,_speed) {}
