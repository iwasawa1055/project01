$(function() {

    $('#InboundDeliveryCarrier').change(function() {
        getDatetime();
        review();
    });
    review();
});

function review() {
    var elem_deca = $('#InboundDeliveryCarrier');
    if (elem_deca.val().indexOf('6') === -1) {
        $('.inbound_pickup_only').hide();
    } else {
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
