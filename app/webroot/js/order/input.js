var AppInputOrder =
{
  a: function () {
    $('#address_id').change(function () {
      AppInputOrder.getDatetime();
    });
  },

  getDatetime: function () {
    var elem_address = $('#address_id');
    var elem_datetime = $('#datetime_cd');

    // 未選択また「追加」を選択
    if (!elem_address.val() || elem_address.val() == -99) {
      elem_datetime.empty();
      return;
    }

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
}

// FirstOrderから移植
var AppAddOrder =
{
  a: function () {
    // ボックス数選択
    $('.js-item-number').change(function () {
      var selector = $(this).data("box_type");
      var number = Number(0);

      $('.js-item-'+ selector).each(function () {
        var set_number = $(this).val();
        var selector_name = $(this).data("name");
        $('input[name='+ selector_name + ']').val(set_number);
        number += Number(set_number);
        // console.log('number:' + number);
      });

      if (number === 0) {
        $('#select_' + selector).html('未選択');
      } else {
        $('#select_' + selector).html('<span>' +  number +'個選択済み</span>');
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
  AppAddOrder.a();
  AppAddOrder.b();
});

