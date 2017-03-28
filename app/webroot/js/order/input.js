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
    if ($('input[name=select-card]:checked').val() === 'default')
    {
      $('.dsn-input-security-code').show('slow');
      $('.dsn-input-change-card').hide('slow');
    } else {
//      console.log('is change');

      $('.dsn-input-security-code').hide('slow');
      $('.dsn-input-change-card').show('slow');
    }
  },

  init_disp4: function() {
    if ($("#select_delivery").val() === '') {
      // アドレス入力を非表示
      $('.dsn-input-new-adress').hide('slow');

      var elem_address = $('#address_id');
      var elem_datetime = $('#datetime_cd');
      $('option:first', elem_datetime).prop('selected', true);
      elem_datetime.attr("disabled", "disabled");

      $.post('/order/getAddressDatetime',
        { address_id: elem_address.val() },
        function(data){
          if (data.result) {
            elem_datetime.empty();
            var optionItems = new Array();
            $.each(data.result, function() {
                optionItems.push(new Option(this.text, this.datetime_cd));
            });
            // 戻る対応でリストをpostする
            $('#select_delivery').val(JSON.stringify(data.result));

            elem_datetime.append(optionItems);
          };
        },
        'json'
      ).always(function() {
        elem_datetime.removeAttr("disabled");
      });
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
  init_disp6: function() {

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

  getDatetime: function () {
    var elem_address = $('#address_id');
    var elem_datetime = $('#datetime_cd');

    // 未選択また「追加」を選択
    if (!elem_address.val() || elem_address.val() == -99) {
      $('.dsn-input-new-adress').show('slow');
      elem_datetime.empty();
      return;
    }

    // アドレス入力を非表示
    $('.dsn-input-new-adress').hide('slow');

    $('option:first', elem_datetime).prop('selected', true);
    elem_datetime.attr("disabled", "disabled");

    $.post('/order/getAddressDatetime',
      { address_id: elem_address.val() },
      function(data){
        if (data.result) {
          elem_datetime.empty();
          var optionItems = new Array();
          $.each(data.result, function() {
              optionItems.push(new Option(this.text, this.datetime_cd));
          });
          // 戻る対応でリストをpostする
          $('#select_delivery').val(JSON.stringify(data.result));

          elem_datetime.append(optionItems);
        };
      },
      'json'
    ).always(function() {
      elem_datetime.removeAttr("disabled");
    });
  },
  getDatetimePostal: function (){
      var elem_postal = $('#postal');
      var elem_datetime = $('#datetime_cd');

      $('option:first', elem_datetime).prop('selected', true);
      elem_datetime.attr("disabled", "disabled");

      // 引数取得
      var params = {};
      params.postal = elem_postal.val();

      // API実行
      if (params.postal != '') {
        $.ajax({
          url: '/order/as_get_address_datetime_by_postal',
          cache: false,
          data: params,
          dataType: 'json',
          type: 'POST'
        }).done(function (data, textStatus, jqXHR) {
          $('#datetime_cd > option').remove();
          // 成功時 お届け日時セット
          elem_datetime.append($('<option>').html('以下からお選びください').val(''));
          $.each(data.results, function (index, datatime) {
            elem_datetime.append($('<option>').html(datatime.text).val(datatime.datetime_cd));
          });
          // 戻る対応でリストをpostする
          $('#select_delivery').val(JSON.stringify(data.results));
        }).fail(function (data, textStatus, errorThrown) {
          // 失敗時 お届け日時リセット
          $('#datetime_cd > option').remove();
          $('#datetime_cd').append($('<option>').html('以下からお選びください').val(''));
        }).always(function (data, textStatus, returnedObject) {
          elem_datetime.removeAttr("disabled");
          //  $('body').airLoader().end();
        });
      } else {
        // お届け日時リセット
        $('#datetime_cd > option').remove();
        $('#datetime_cd').append($('<option>').html('以下からお選びください').val(''));
        elem_datetime.removeAttr("disabled");
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
  AppInputOrder.init_disp1();
  AppInputOrder.init_disp2();
  AppInputOrder.init_disp3();
  AppInputOrder.init_disp4();
  AppInputOrder.init_disp5();
  AppInputOrder.init_disp6();
  AppAddOrder.a();
  AppAddOrder.b();
});

