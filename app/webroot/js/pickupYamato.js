var pickup_date_time;

var PickupYamato =
{

    getDateTime: function()
    {
        var week_text = ["(日)", "(月)", "(火)", "(水)", "(木)", "(金)", "(土)"];

        $.ajax({
          url: '/ajax/as_getYamatoDatetime',
          cache: false,
          dataType: 'json',
          type: 'POST'
        }).done(function (data, textStatus, jqXHR) {
            console.log(data);
            if (data.results) {
                pickup_date_time = data.results;
                $('#select-pickup-date option').remove();
                var default_date = null;
                //for (var item in data.result) {
                for (var item in pickup_date_time) {
                    if (!default_date) {
                        default_date = item;
                    }
                    //console.log(new Date(item));
                    // 集荷日程をセット
                    var date_obj = new Date(item);
                    var week = date_obj.getDay();
                    var pickup_date_text = item.replace(/-/g, '/') + ' ' + week_text[week]; 
                    $('#DayCd').append($('<option>').text(pickup_date_text).attr('value', item));
                }

                // 現在登録されている集荷依頼日
                var pickup_date = null;
                if ($('#pickup_date').val()) {
                    pickup_date = $('#pickup_date').val();
                } else {
                    pickup_date = default_date;
                }
                $("#DayCd").val(pickup_date);

                $('#TimeCd option').remove();
                for(var item in pickup_date_time[pickup_date]) {
                    var pickup_time_text = pickup_date_time[pickup_date][item];
                    $('#TimeCd').append($('<option>').text(pickup_time_text).attr('value', item));
                }

                if ($('#pickup_time_code').val()) {
                   $('#TimeCd').val($('#pickup_time_code').val());
                } else {
                    for(var item in pickup_date_time[$('#DayCd').val()]) {
                        $('#TimeCd').val(item);
                        break;
                    }
                }
            };

        }).fail(function (data, textStatus, errorThrown) {
            console.log(data);

        }).always(function (data, textStatus, returnedObject) {
            console.log(data);

        });
    },
    changeSelectPickup: function()
    {
        $('#DayCd').change(function() {
            var change_pickup_date = $('#DayCd option:selected').val();
            $('#TimeCd option').remove();
            console.log(change_pickup_date);
            for(var item in pickup_date_time[change_pickup_date]) {
                var pickup_time_text = pickup_date_time[change_pickup_date][item];
                $('#TimeCd').append($('<option>').text(pickup_time_text).attr('value', item));
            }
        });
    }
}
