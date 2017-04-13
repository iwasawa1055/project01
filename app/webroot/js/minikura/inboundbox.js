var DELIVERY_ID_PICKUP = '6';
var DELIVERY_ID_MANUAL = '7';
$(function() {

    console.log("aaa");
    $('#InboundDeliveryCarrier').change(function() {
        console.log("ddd");
        getDatetime();
        review();
    });
    review();
});

function review() {
    var elem_deca = $('#InboundDeliveryCarrier');
    if (elem_deca.val().indexOf(DELIVERY_ID_PICKUP) === -1) {
        $('.inbound_pickup_only').hide();
    } else {
        // 集荷の場合
        $('.inbound_pickup_only').show();
    }
}

function getDatetime() {
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
            if (data.result.date) {
                var optionItems = new Array();
                $.each(data.result.date, function() {
                    optionItems.push(new Option(this.text, this.date_cd));
                });
                elem_day.append(optionItems);
            };
            if (data.result.time) {
                var optionItems = new Array();
                $.each(data.result.time, function() {
                    optionItems.push(new Option(this.text, this.time_cd));
                });
                elem_time.append(optionItems);
            };
        },
        'json'
    ).always(function() {
        elem_day.removeAttr("disabled");
        elem_time.removeAttr("disabled");
    });
};
