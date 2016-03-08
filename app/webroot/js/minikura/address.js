$(function() {
    $('.search_address_postal').blur(function() {
        var postal = $(this).val();
        $('.address_pref').val('');
        $('.address_address1').val('');
        $('.address_address2').val('');

        $('.search-address-error-message').remove();
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
        var pNotFound = '<p class="error-message search-address-error-message">該当する住所が見つかりませんでした。</p>';
        var pNotAvailable = '<p class="error-message search-address-error-message">住所検索機能は現在利用できません。</p>';
        if (status === google.maps.GeocoderStatus.OK &&
            0 < results.length &&
            results[0].address_components) {
            var ad = results[0].address_components;

            if (ad[4] && ad[4].short_name === 'JP') {
                // 該当あり
                $('.address_pref').val(ad[3].long_name);
                $('.address_address1').val(ad[2].long_name);
                $('.address_address2').val(ad[1].long_name);
                var postcode = ad[0].short_name;
                $('.search_address_postal').val(postcode);
                return;
            }

            $('.search_address_postal').after(pNotFound);

        } else if (status === google.maps.GeocoderStatus.ZERO_RESULTS) {
            // 該当しない
            $('.search_address_postal').after(pNotFound);

        } else {
            // 利用できない
            $('.search_address_postal').after(pNotAvailable);
        }
    });
}
