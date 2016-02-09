$(function() {

    $('#InboundDeliveryCarrier').change(function() {
        getDatetime();
    });

    function getDatetime() {
        var elem_deca = $('#InboundDeliveryCarrier');
        var elem_day = $('#InboundDayCd');
        var elem_time = $('#InboundTimeCd');

        $('option:first', elem_day).prop('selected', true);
        elem_day.attr("disabled", "disabled");
        elem_day.empty();


        $.post('/inbound/box/getInboundDate', {
                delivery_carrier: elem_deca.val()
            },
            function(data) {
                if (data.result) {

                    var optionItems = new Array();
                    $.each(data.result, function() {
                        optionItems.push(new Option(this.text, this.date_cd));
                    });
                    elem_day.append(optionItems);
                };
            },
            'json'
        ).always(function() {
            elem_day.removeAttr("disabled");
        });


        $('option:first', elem_time).prop('selected', true);
        elem_time.attr("disabled", "disabled");
        elem_time.empty();

        $.post('/inbound/box/getInboundTime', {
                delivery_carrier: elem_deca.val()
            },
            function(data) {
                if (data.result) {

                    var optionItems = new Array();
                    $.each(data.result, function() {
                        optionItems.push(new Option(this.text, this.time_cd));
                    });
                    elem_time.append(optionItems);
                };
            },
            'json'
        ).always(function() {
            elem_time.removeAttr("disabled");
        });
    };
});
