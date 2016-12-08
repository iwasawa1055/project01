$(function() {
  $('.aircontent_select').change(function() {
    changeAircontent($('.aircontent_select:checked'));
  });

  $('#OutboundAddressId').change(function() {
    getDatetime();
    initExpireDate();
  });

  $("#OutboundDatetimeCd").change(function() {
    getExpireDate();
  });

  initDisp();
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

  $.post('/rentalcase/getAddressDatetime', {
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

  // 日付選択にエラーがなく返却日エラーの場合 返却日を保持していた場合 返却予定日を表示
  if(!$('.datetime_select .error-message')[0]) {
    if($('.outbound-expire .error-message')[0] || $('#OutboundLimitExpire').val() !== '') {
      getExpireDate();
      $('.outbound-expire').show();
    }
  }

  if($('.aircontent_select:checked').length > 0) {
    changeAircontent($('.aircontent_select:checked'));
  }

  // ポイント部分表示 ポイント使用、もしくはエラーあり
  if((Number($('#PointUseUsePoint').val()) !== 0) || $('.input-point .error-message')[0]) {
    $('#collapse-point').addClass('in');
    $('#collapse-point').attr({'aria-expanded' : 'true'});
  }
};

function changeAircontent(elm) {
  if (elm.val() === '1') {
    $('.datetime_select').hide();
    $('.outbound-expire').hide();
    $('.aircontent').show();
  } else {
    $('.datetime_select').show();
    if ($('#OutboundLimitExpire').val() !== '') {
      $('.outbound-expire').show();
    }
    $('.aircontent').hide();
  }
};

function getExpireDate() {
  elem_outbound_datetime_cd = $("#OutboundDatetimeCd");
  elem_outbound_expire_cd = $("#OutboundLimitExpireCd");

  // 未選択また「追加」を選択
  if (!elem_outbound_datetime_cd.val() || elem_outbound_datetime_cd.val() == '0000-00-00') {
    initExpireDate();
    return;
  }

  // 日付未選択時のエラーメッセージをクリア
  if($('.datetime_select .error-message')[0]) {
    $('.outbound-expire .error-message').remove();
  }

  $('option:first', elem_outbound_expire_cd).prop('selected', true);
  elem_outbound_expire_cd.attr("disabled", "disabled");
  elem_outbound_expire_cd.empty();

  elem_outbound_expire_cd.removeAttr("disabled");

  selected_datetime_cd = elem_outbound_datetime_cd.val();
  selected_date = selected_datetime_cd.slice(0, 10).replace(/[-]/g, '/');
  selected_date = new Date(selected_date);
  selected_date.setDate(selected_date.getDate() + 7);
  expire_datetime = selected_date.toLocaleString();
  text_month = selected_date.getMonth() + 1;
  expire_date = selected_date.getFullYear() + '-' + text_month + '-' + selected_date.getDate();


  $('#outbound-expire-date').html(expire_date + 'までに返却してください');
  $('#OutboundLimitExpire').val(expire_date);
  $('.outbound-expire').css('display', 'block');
}

function initExpireDate() {
    $('.outbound-expire .error-message').remove();
    $('#OutboundLimitExpire').val('');
    $("#OutboundDatetimeCd").empty();
    $('.outbound-expire').hide();
}