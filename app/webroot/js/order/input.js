var AppInputOrder =
{

  a: function () {
    $('input[name="select-card"]#as-card').change(function () {
      $('.dsn-input-security-code').toggle('slow');
      $('.dsn-input-change-card').hide('slow');
      $('.dsn-input-new-card').hide('slow');
    });
  },
  b: function () {
    $('input[name="select-card"]#change-card').change(function () {
      $('.dsn-input-change-card').toggle('slow');
      $('.dsn-input-security-code').hide('slow');
      $('.dsn-input-new-card').hide('slow');
    });
  },
  c: function () {
    $('input[name="select_address"]#list_address').change(function () {
      $('.dsn-input-new-adress').toggle('slow');
    });
  },
  d: function () {
    $('input[name="select_address"]#add_address').change(function () {
      $('.dsn-input-new-adress').toggle('slow');
    });
  },
  e: function () {
    $('#address_id').change(function () {
      AppInputOrder.getDatetime();
      // $('.dsn-input-new-adress').hide('slow');
    });
  },

  f: function () {
    $('#postal').change(function() {
      AppInputOrder.getDatetimePostal();
    });
  },
  g: function()
  {
    // validation メッセージが表示される時に、ページ上部に表示する
    if ($('span').hasClass('validation')) {
      $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.dev-wrapper');
    }
  },
  h: function()
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
  i: function()
  {
      // 初回表示時
      var address_id = $('#address_id').val();
      if (address_id !== 'add') {
          $('.order-input-address').hide();
      }
      // 選択住所変更時
      $('#address_id').change(function() {
          $('.order-input-address').hide();
          if ($(this).val() === 'add') {
              $('.order-input-address').slideDown(Speed, Ease);
          } else {
              $('.order-input-address').slideUp(Speed, Ease);
          }
      });
  },
  j: function()
  {
      var cc = $('#input-cc');
      var sc = $('#input-sc');
      var nc = $('#input-nc');

      var is_update = $('#is_update').val();
      // カード更新
      if (is_update === '1') {
          cc.hide();
          nc.hide();
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
  k: function ()
  {
    $('#hanger_check input').change(function () {
      if ($(this).prop('checked')) {
        $('#execute').css("opacity", "1");

        if ($('#hanger-check-error').length) {
          $('#hanger-check-error').remove();
        }
      } else {
        $('#execute').css("opacity", "0.5");
      }
    });

    $('#execute').on('mouseup', function(){
      // ハンガー未選択時
      if ($('.select_hanger').css('display') == 'none') {
        $(this).closest("form").submit();
      } else {
        // ハンガー選択時
        if ($('#hanger_check input').prop('checked')) {
          $(this).closest("form").submit();
        } else {
          if ($('#hanger-check-error').length == 0) {
            $('#hanger_check').parent('label').parent('li').append('<p class="valid-il" id="hanger-check-error">お届け日時のご確認をお願いします。</p>');
          }
        }
      }
    });
  },

  init_disp1: function () {
    // 合計点数の初期化
    var total_number = Number(0);
    $('.js-item-number').each(function () {
      var set_number = $(this).val();
      var selector_name = $(this).data("name");
      $('input[name='+ selector_name + ']').val(set_number);
      total_number += Number(set_number);
      // console.log('number:' + number);
    });

    if (total_number === 0) {
      $('#js-item-total').html('0点');
    } else {
      $('#js-item-total').html(total_number +'点');
    }
  },
  init_disp2: function () {
    // 住所入力の場合
    if ($('#address_id').val() == -99)
    {
      $('.dsn-input-new-adress').show('slow');
    } else {
      $('.dsn-input-new-adress').hide('slow');
    }

  },
  init_disp3: function () {
    if ($('.dsn-select-cards').css('display') === 'none')
    {
      $('.dsn-input-security-code').hide('slow');
      $('.dsn-input-change-card').show('slow');
    } else if ($('input[name=select-card]:checked').val() === 'default') {
      $('.dsn-input-security-code').show('slow');
      $('.dsn-input-change-card').hide('slow');
    } else {
//      console.log('is change');

      $('.dsn-input-security-code').hide('slow');
      $('.dsn-input-change-card').show('slow');
    }
  },

  init_disp4: function() {
    // 住所デフォルトでお届け先が非表示になる場合
    // 住所追加ではない場合
    if($('#address_id').val() != -99 ){
      // お届先が未選択状態
      if ($("#datetime_cd").val() === null) {
        // お届け希望リスト生成
        AppInputOrder.getDatetime();
      }
    }
  },
  init_disp5: function() {

    // 住所追加の場合
    if($('#address_id').val() == -99 ){

      // 郵便番号入力済
      if ($("#postal").val() !== '') {

        // お届け先希望未指定
        if ($("#datetime_cd").val() === null) {
          // お届け先を追加
          AppInputOrder.getDatetimePostal();
        }
      }
    }
  },
  init_disp6: function () {
    // タイプ別箱の合計値設定
    var box_type_list = [
      {class : 'box_type_hanger',   id : 'hanger_total'  , flag : 'hanger'},
      {class : 'box_type_hako',     id : 'hako_total'    , flag : 'other'},
      {class : 'box_type_mono',     id : 'mono_total'    , flag : 'other'},
      {class : 'box_type_library',  id : 'library_total' , flag : 'other'},
      {class : 'box_type_cleaning', id : 'cleaning_total', flag : 'other'},
    ];
    var flag_total = [];
    flag_total['other']  = 0;
    flag_total['hanger'] = 0;
    $.each(box_type_list, function (index, box) {
        var type_total = 0;
        $('.' + box.class).each(function () {
          var type_value = $(this).val();
          if ($.isNumeric(type_value)) {
            type_total += parseFloat(type_value);
          }
        });
        // タイプ別箱合計値セット
        $('#' + box.id).html(type_total);
        // 購入タイプ(API)
        flag_total[box.flag] += parseFloat(type_total);
    });

    // hanger other 出力エリア
    if (flag_total['hanger'] > 0 && flag_total['other'] > 0) {
      $('.select_other').show();
      $('.select_hanger').show();
    } else if (flag_total['hanger'] > 0 && flag_total['other'] == 0) {
      $('.select_other').hide();
      $('.select_hanger').show();
    } else {
      $('.select_other').show();
      $('.select_hanger').hide();
    }

    var valueStep = 1;
    var minValue  = 0;
    var maxValue  = 20;
    // var flagType  = '';
    $('.btn-spinner').on('mousedown', function() {

      // ハンガー用出力エリア制御
      if($(this).closest(".type_other").length > 0){
        flagType = 'other';
      } else {
        flagType = 'hanger';
      }
      var itemValue  = parseInt($(this).parents('.spinner').find('.input-spinner').val());
      var btnType  = $(this).attr('name');
      if (btnType === 'spinner_down') {
        if (itemValue > minValue) {
          flag_total[flagType] = parseInt(flag_total[flagType]) - valueStep;
        }
      }
      if (btnType === 'spinner_up') {
        if (itemValue < maxValue) {
          flag_total[flagType] = parseInt(flag_total[flagType]) + valueStep;
        }
      }
      if (flag_total['hanger'] > 0 && flag_total['other'] > 0) {
        $('.select_other').show('slow');
        $('.select_hanger').show('slow');
      } else if (flag_total['hanger'] > 0 && flag_total['other'] == 0) {
        $('.select_other').hide('slow');
        $('.select_hanger').show('slow');
      } else {
        $('.select_other').show('slow');
        $('.select_hanger').hide('slow');
      }

      // 確認画面遷移ボタン制御
      if (flag_total['hanger'] > 0) {
        if ($('#hanger_check input').prop('checked')) {
          $('#execute').css("opacity", "1");
        } else {
          $('#execute').css("opacity", "0.5");
        }
      } else {
        $('#execute').css("opacity", "1");
      }
    });
    return false;
  },
  init_disp7: function () {
    // ハンガー時にボタンを薄くする
    if ($('.select_hanger').css('display') != 'none') {
      $('#execute').css("opacity", "0.5");
    }
  },

  getDatetime: function () {
    var elem_address = $('#address_id');
    var elem_datetime = $('#datetime_cd');

    // 未選択また「追加」を選択
    if (!elem_address.val() || elem_address.val() == -99) {
      // TODO これいらないけど、変わるものを入れる必要あるかも
      $('.dsn-input-new-adress').show('slow');
      elem_datetime.empty();
      return;
    }

    // 引数取得
    var data = {
      "address_id" : elem_address.val()
    };

    $.ajax({
        url      : '/order/as_get_datetime_by_address_id',
        cache    : false,
        data     : data,
        dataType : 'json',
        type     : 'POST'
    }).done(function (data, textStatus, jqXHR) {
        elem_datetime.empty();
        $.each(data.result.results, function (index, datatime) {
            elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
        });
    }).fail(function (data, textStatus, errorThrown) {
        // TODO どうするかな
    }).always(function (data, textStatus, returnedObject) {
        // TODO これ必要？
        elem_datetime.removeAttr("disabled");
    });
  },

  getDatetimePostal: function (){
      var elem_postal = $('#postal');
      var elem_datetime = $('#datetime_cd');

      $('option:first', elem_datetime).prop('selected', true);
      elem_datetime.attr("disabled", "disabled");

      // 引数取得
      var data = {
          "postal" : elem_postal.val()
      };

      // API実行
      if (data.postal != '') {
        $.ajax({
          url      : '/order/as_get_datetime_by_postal',
          cache    : false,
          data     : data,
          dataType : 'json',
          type     : 'POST'
        }).done(function (data, textStatus, jqXHR) {
            elem_datetime.empty();
            $.each(data.result.results, function (index, datatime) {
                elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
            });
        }).fail(function (data, textStatus, errorThrown) {
            // TODO どうするかな
        }).always(function (data, textStatus, returnedObject) {
            // TODO これ必要？
            elem_datetime.removeAttr("disabled");
        });
      } else {
        // お届け日時リセット
        $('#datetime_cd > option').remove();
        $('#datetime_cd').append($('<option>').html('住所設定をお願いします').val(''));
        elem_datetime.attr("disabled");
      }
    },
}

// FirstOrderから移植
var AppAddOrder =
{
  a: function () {
    // ボックス数選択
    $('.js-item-number').change(function () {
      var selector = $(this).data("box_type");
      var number = Number(0);
      var total_number = Number(0);

      $('.js-item-'+ selector).each(function () {
        var set_number = $(this).val();
        var selector_name = $(this).data("name");
        $('input[name='+ selector_name + ']').val(set_number);
        number += Number(set_number);
        // console.log('number:' + number);
      });

      $('.js-item-number').each(function () {
        var set_number = $(this).val();
        var selector_name = $(this).data("name");
        $('input[name='+ selector_name + ']').val(set_number);
        total_number += Number(set_number);
        // console.log('number:' + number);
      });

      if (number === 0) {
        $('#select_' + selector).html('未選択');
      } else {
        $('#select_' + selector).html('<span>' +  number +'個選択済み</span>');
      }

      if (total_number === 0) {
        $('#js-item-total').html('0点');
      } else {
        $('#js-item-total').html(total_number +'点');
      }
    });
  },

  b: function () {
    $('.btn-submit').on('click', function (e) {
      $('form').submit();
    });
  },
}

/*
 * document ready
 * */
$(function()
{
  AppInputOrder.a();
  AppInputOrder.b();
  AppInputOrder.c();
  AppInputOrder.d();
  AppInputOrder.e();
  AppInputOrder.f();
  AppInputOrder.g();
  AppInputOrder.h();
  AppInputOrder.i();
  AppInputOrder.j();
  AppInputOrder.k();
  AppInputOrder.init_disp1();
  AppInputOrder.init_disp2();
  AppInputOrder.init_disp3();
  AppInputOrder.init_disp4();
  AppInputOrder.init_disp5();
  AppInputOrder.init_disp6();
  AppInputOrder.init_disp7();
  AppAddOrder.a();
  AppAddOrder.b();
});

