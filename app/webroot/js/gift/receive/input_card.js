var AppInputOrder =
{
  a: function () {
    // ギフトコード確認ボタン押下
    $('#check_gift_cd').on('click', function (e) {

      // ギフトコードエラー削除
      $('.input_gift_cd_area > p.valid-il').remove();
      // ギフト説明(h4)削除
      $('h4#gift_head_message').remove();

      // ギフト内容を非表示
      $(".item").each(function(i) {
        $(this).hide();
      });

      var gift_cd = $('#gift_cd').val();

      // API実行
      if (gift_cd) {

        // 引数取得
        var data = {
          "gift_cd" : gift_cd
        };

        $.ajax({
          url      : '/gift/receive/as_get_gift_data',
          cache    : false,
          data     : data,
          dataType : 'json',
          type     : 'POST'
        }).done(function (data, textStatus, jqXHR) {
          if (!data.status) {
            // 該当データなし
            $('.input_gift_cd_area').append('<p class="valid-il">該当するギフトコードが存在しません</p>');
          } else {
            // 該当データあり
            $('li.gift-info').before('<h4 id="gift_head_message">ご利用可能な商品</h4>');
            $.each(data.result, function (index, kit_data) {
              var target_kit_id = '#kit_' + kit_data.kit_cd;
              // 個数
              $(target_kit_id).find('p.text-number>span').text(kit_data.kit_cnt);
              // エリア表示
              $(target_kit_id).show();
            });
          }
        }).fail(function (data, textStatus, errorThrown) {
          $('.input_gift_cd_area').append('<p class="valid-il">画面をリロードしてください</p>');
        }).always(function (data, textStatus, returnedObject) {
        });

      } else {
        // お届け日時リセット
        $('.input_gift_cd_area').append('<p class="valid-il">ギフトコードを入力してください</p>');
      }
    });
  },

  b: function () {
  },
  c: function()
  {
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
  },
  init_disp3: function () {
    $('#execute').css("opacity", "0.5");
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

