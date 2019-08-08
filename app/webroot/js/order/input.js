var AppInputOrder =
{
  a: function () {
    $('#address_id').change(function () {
      AppInputOrder.getDatetime();
    });
  },

  b: function () {
    $('#postal').change(function() {
      AppInputOrder.getDatetimePostal();
    });
  },
  c: function()
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
  d: function()
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
  e: function()
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
  f: function ()
  {
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
        }
      } else {
        $('#execute').css("opacity", "0.5");
      }
    });
  },
  g: function ()
  {
    $('#execute').on('mouseup', function(){
      var exe_flag = true;
      $(".caution-box input").each(function(i) {
        if (!$(this).prop("checked")) {
          alert('注意事項をご確認ください');
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
  h: function()
  {
    // validation メッセージが表示される時に、ページ上部に表示する
    if ($('p').hasClass('valid-il')) {
      $('<div class="dsn-form"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> 入力内容をご確認ください</div></div>').insertBefore('div.dev-wrapper');
    }
  },
  i: function()
  {
    var valid = $(".valid-il").get(0);
    if (valid) {
      var position = valid.offsetTop;
      $('body,html').animate({scrollTop: position}, 'slow');
    }
  },

  init_disp1: function() {
    // 初回表示時
    if ($("#datetime_cd").val() === null) {
      // お届け希望リスト生成
      AppInputOrder.getDatetime();
    }
  },
  init_disp2: function () {
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

    // お届け日時
    if (flag_total['other'] == 0 && (flag_total['hanger'] > 0 && flag_total['hanger'] < 3)) {
      $('.select_other').hide();
    } else {
      $('.select_other').show();
    }

    var valueStep = 1;
    var minValue  = 0;
    var maxValue  = 20;
    // var flagType  = '';
    $('.btn-spinner').on('click', function() {

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

      // カードエリア出力
      if($(this).closest("#cleaning").length > 0){
        var cleaningValue  = parseInt($("#cleaning").find('.input-spinner').val());
        if (btnType === 'spinner_down') {
          if (itemValue > minValue) {
            cleaningValue = cleaningValue - valueStep;
          }
        }
        if (btnType === 'spinner_up') {
          if (itemValue < maxValue) {
            cleaningValue = cleaningValue + valueStep;
          }
        }
        if (cleaningValue > 0) {
          $('.select_card').show('slow');
        } else {
          $('.select_card').hide('slow');
        }
      }

      // お届け日時
      if (flag_total['other'] == 0 && (flag_total['hanger'] > 0 && flag_total['hanger'] < 3)) {
        $('.select_other').hide('slow');
      } else {
        $('.select_other').show('slow');
      }
    });
    return false;
  },
  init_disp3: function () {
    // ハンガー時にボタンを薄くする
    if ($('.caution-box').css('display') != 'none') {
      $('#execute').css("opacity", "0.5");
    }
  },

  getDatetime: function () {
    var elem_address = $('#address_id');
    var elem_datetime = $('#datetime_cd');

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
  AppInputOrder.init_disp1();
  AppInputOrder.init_disp2();
  AppInputOrder.init_disp3();
});

