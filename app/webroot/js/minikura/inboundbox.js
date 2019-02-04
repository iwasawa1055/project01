var DELIVERY_ID_PICKUP = '6';
var DELIVERY_ID_MANUAL = '7';
var pickup_date_time;
$(function() {

    $('#InboundDeliveryCarrier').change(function() {
        getDatetime();
        review();
    });
    review();

    checkIncludeMonoBox()
    $("input[type='checkbox']").click(function() {
        checkIncludeMonoBox();
    });

    changeSelectPickup();
});

function review() {
    var elem_deca = $('#InboundDeliveryCarrier');
    if (elem_deca.val().indexOf(DELIVERY_ID_PICKUP) === -1) {
        $('.inbound_pickup_only').hide();
    } else {
        // 集荷の場合
        $('.inbound_pickup_only').show();
        if ($(".lastname").val() !== '')
        {
            $(".name-form-group").css('display', 'none');
        }
    }
}

function getDatetime(day_cd) {
    var elem_deca = $('#InboundDeliveryCarrier');
    var elem_day = $('#InboundDayCd');
    var elem_time = $('#InboundTimeCd');

    $('option:first', elem_day).prop('selected', true);
    elem_day.attr("disabled", "disabled");
    elem_day.empty();
    $('option:first', elem_time).prop('selected', true);
    elem_time.attr("disabled", "disabled");
    elem_time.empty();

    // 未選択また集荷でない場合
    if (!elem_deca.val() || elem_deca.val().indexOf(DELIVERY_ID_PICKUP) === -1) {
      return;
    }
    $.post('/inbound/box/getInboundDatetime', {
            Inbound: {delivery_carrier: elem_deca.val()}
        },
        function(data) {
                console.log(data.result.datetime);
            if (data.result.datetime) {
                // 日付セレクトボックス
                var date = null;
                var optionItems = new Array();
                $.each(data.result.date, function() {
                    if (day_cd) {
                        date = day_cd;
                    }
                    if (!date) {
                        date = this.date_cd;
                    }
                    optionItems.push(new Option(this.text, this.date_cd));
                });
                elem_day.append(optionItems);
                elem_day.val(date);

                // 時間セレクトボックス
                pickup_date_time = data.result.datetime.results;
                //pickup_date_time = data.result.datetime;
                console.log(data.result.time);
                var optionItems = new Array();
                for(var item in pickup_date_time[date]) {
                    var pickup_time_text = pickup_date_time[date][item];
                    optionItems.push(new Option(pickup_time_text, item));
                }
                elem_time.append(optionItems);
            };
        },
        'json'
    ).always(function() {
        elem_day.removeAttr("disabled");
        elem_time.removeAttr("disabled");
    });
};

function checkIncludeMonoBox() {
    // 初期化としてお知らせを消す
    $('#dev_inbound_notice').hide();

    var check_list = $('.inbound_box_select_checkbox').children('input[type="checkbox"]:checked');
    check_list.each(function(index, element) {
        if ($(element).attr('name').match(/MN-/)) {
            $('#dev_inbound_notice').show();
        }
        if ($(element).attr('name').match(/ML-/)) {
            $('#dev_inbound_notice').show();
        }
    });
}

function changeSelectPickup() {
    $('#InboundDayCd').change(function() {
        var change_pickup_date = $('#InboundDayCd option:selected').val();
        $('#InboundTimeCd option').remove();
        if (pickup_date_time) {
            for(var item in pickup_date_time[change_pickup_date]) {
                var pickup_time_text = pickup_date_time[change_pickup_date][item];
                $('#InboundTimeCd').append($('<option>').text(pickup_time_text).attr('value', item));
            }
        } else {
            getDatetime(change_pickup_date);           
        }
    });
}
