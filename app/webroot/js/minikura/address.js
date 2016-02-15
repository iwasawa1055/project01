$(function() {
    $('.search_address_postal').blur(function() {
        var postal = $(this).val();
        $('.address_pref').val('');
        $('.address_address1').val('');
        $('.address_address2').val('');

        searchAddress(postal);
    });
});
function searchAddress(postalCode) {
    var geo = new google.maps.Geocoder();
    geo.geocode({
        address: postalCode,
        language: 'jp',
        region: 'jp'
    }, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK &&
            0 < results.length &&
            results[0].address_components) {
            var ad = results[0].address_components;
            console.dir(results);

            if (ad[4] && ad[4].short_name === 'JP') {
                $('.address_pref').val(ad[3].long_name);
                $('.address_address1').val(ad[2].long_name);
                $('.address_address2').val(ad[1].long_name);
            }

            console.log('ZERO');

        } else if (status === google.maps.GeocoderStatus.ZERO_RESULTS) {
            // 該当しない
            console.log('ZERO');
        } else {
            // 利用できない
            console.log('error');
        }
    });
}
