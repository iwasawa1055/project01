$(function() {

    $('#OutboundAddressId').change(function() {
        getDatetime();
    });

    function getDatetime() {
        var elem_address = $('#OutboundAddressId');
        var elem_datetime = $('#OutboundDatetimeCd');

        $('option:first', elem_datetime).prop('selected', true);
        elem_datetime.attr("disabled", "disabled");

        $.post('/outbound/getAddressDatetime', {
                address_id: elem_address.val()
            },
            function(data) {
                if (data.result) {
                    elem_datetime.empty();
                    var optionItems = new Array();
                    $.each(data.result, function() {
                        optionItems.push(new Option(this.text, this.datetime_cd));
                    });
                    elem_datetime.append(optionItems);
                };
            },
            'json'
        ).always(function() {
            elem_datetime.removeAttr("disabled");
        });
    };
});