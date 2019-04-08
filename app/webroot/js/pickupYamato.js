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
            if (data.results) {
                pickup_date_time = data.results;
                $('#select-pickup-date option').remove();
                var default_date = null;
                //for (var item in data.result) {
                for (var item in pickup_date_time) {
                    if (!default_date) {
                        default_date = item;
                    }
                    // 集荷日程をセット
                    var date_obj = new Date(item);
                    var week = date_obj.getDay();
                    var pickup_date_text = item.replace(/-/g, '/') + ' ' + week_text[week]; 
                    $('#day_cd').append($('<option>').text(pickup_date_text).attr('value', item));
                }

                // 現在登録されている集荷依頼日
                var pickup_date = null;
                if ($('#pickup_date').val()) {
                    pickup_date = $('#pickup_date').val();
                } else {
                    pickup_date = default_date;
                }
                $("#day_cd").val(pickup_date);

                $('#time_cd option').remove();
                for(var item in pickup_date_time[pickup_date]) {
                    var pickup_time_text = pickup_date_time[pickup_date][item];
                    $('#time_cd').append($('<option>').text(pickup_time_text).attr('value', item));
                }

                if ($('#pickup_time_code').val()) {
                   $('#time_cd').val($('#pickup_time_code').val());
                } else {
                    for(var item in pickup_date_time[$('#day_cd').val()]) {
                        $('#time_cd').val(item);
                        break;
                    }
                }
            }
        }).fail(function (data, textStatus, errorThrown) {
        }).always(function (data, textStatus, returnedObject) {
        });
    },
    changeSelectPickup: function()
    {
        $('#day_cd').change(function() {
            var change_pickup_date = $('#day_cd option:selected').val();
            $('#time_cd option').remove();
            for(var item in pickup_date_time[change_pickup_date]) {
                var pickup_time_text = pickup_date_time[change_pickup_date][item];
                $('#time_cd').append($('<option>').text(pickup_time_text).attr('value', item));
            }
        });
    }
}
