var AppInputOrder =
{
  DELIVERY_ID_PICKUP : '6',
  DELIVERY_ID_MANUAL : '7',

  a: function(){

    var elem_day = $('#InboundDayCd');
    var elem_time = $('#InboundTimeCd');

    if(elem_day.val() === null) {
      $('option:first', elem_day).prop('selected', true);
      elem_day.attr("disabled", "disabled");
      elem_day.empty();
      $('option:first', elem_time).prop('selected', true);
      elem_time.attr("disabled", "disabled");
      elem_time.empty();

      $.post('/inbound/box/getInboundDatetime', {
            Inbound: {delivery_carrier: '6_1'}
          },
          function (data) {
            if (data.result.date) {
              var optionItems = new Array();
              $.each(data.result.date, function () {
                optionItems.push(new Option(this.text, this.date_cd));
              });
              elem_day.append(optionItems);

              $('#select_delivery_day').val(JSON.stringify(data.result.date));
            }
            ;
            if (data.result.time) {
              var optionItems = new Array();
              $.each(data.result.time, function () {
                optionItems.push(new Option(this.text, this.time_cd));
              });
              elem_time.append(optionItems);

              $('#select_delivery_time').val(JSON.stringify(data.result.time));
            }
            ;
          },
          'json'
      ).always(function () {
        elem_day.removeAttr("disabled");
        elem_time.removeAttr("disabled");
      });
    }
  },

  b: function () {
    // 預け入れ方法の選択初期化
    if($("#yamato").prop('checked')) {
      $('.dsn-arrival').hide('fast');
      $('.dsn-yamato').show('fast');
    } else {
      $('.dsn-arrival').show('fast');
      $('.dsn-yamato').hide('fast');
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
});

