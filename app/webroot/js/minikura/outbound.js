$(function() {
  $('.aircontent_select').change(function() {
    changeAircontent($('.aircontent_select:checked'));
  });

  $('#OutboundAddressId').change(function() {
    getDatetime();
  });

  initDisp();

  if ($(".lastname").val() !== '')
  {
      $(".name-form-group").css('display', 'none');
  }
  
});
function getDatetime() {
  var elem_address = $('#OutboundAddressId');
  var elem_datetime = $('#OutboundDatetimeCd');
  $('.aircontent_select').prop('checked', false);
  $('.aircontent_text').val('');

  // 未選択また「追加」を選択
  if (!elem_address.val() || elem_address.val() == -99) {
    elem_datetime.empty();
    return;
  }

  $('option:first', elem_datetime).prop('selected', true);
  elem_datetime.attr("disabled", "disabled");
  elem_datetime.empty();

  $.post('/outbound/getAddressDatetime', {
          address_id: elem_address.val()
      },
      function(data) {
          if (data.result) {
              var optionItems = new Array();
              $.each(data.result, function() {
                  optionItems.push(new Option(this.text, this.datetime_cd));
              });
              elem_datetime.append(optionItems);
              // お届け先表示切り替え
              $('.datetime_select').toggle(!data.isIsolateIsland);
              $('.aircontent').hide(!data.isIsolateIsland);
              $('.isolate_island_select').toggle(data.isIsolateIsland);

              $('#isolateIsland').val(data.isIsolateIsland);
          };
      },
      'json'
  ).always(function() {
      elem_datetime.removeAttr("disabled");
  });
};

function initDisp() {
  $('.isolate_island_select').hide();
  $('.datetime_select').show();
  $('.aircontent').hide();

  if ($('#isolateIsland').val() !== '') {
    $('.isolate_island_select').show();
    $('.datetime_select').hide();
  }

  if($('.aircontent_select:checked').length > 0) {
    changeAircontent($('.aircontent_select:checked'));
  }
};

function changeAircontent(elm) {
  if (elm.val() === '1') {
    $('.datetime_select').hide();
    $('.aircontent').show();
  } else {
    $('.datetime_select').show();
    $('.aircontent').hide();
  }
};
