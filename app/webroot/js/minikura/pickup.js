var pickup_date_time;

$(function() {
    // お届先住所追加選択時の処理、それ以外の処理 
    changeSelectAddAddressPickup();
    // 郵便番号検索
    blurSearchAddressPostal();
    //集荷日を選択時に集荷時間をセットする
    PickupYamato.changeSelectPickup();
    // 集荷の住所セレクトボックス表示制御
    dispAddressAdd();
    // 集荷日と集荷時間取得
    PickupYamato.getDateTime();

    //** Auto Kana
    $('input.lastname').airAutoKana(
    {
        dest: 'input.lastname_kana',
        katakana: true
    });
    $('input.firstname').airAutoKana(
    {
        dest: 'input.firstname_kana',
        katakana: true
   });
});

function changeSelectAddAddressPickup() {
    $('select.select-add-address-pickup').change(function() {
        if ($(this).val() == '-99') {
            $('.input-address').show();
        } else {
            $('.input-address').hide();
            $('#address_id').val($('#select-add-address-pickup option:selected').val());
        }
    });
}

function blurSearchAddressPostal() {
    $('.search_address_postal').blur(function() {
        var postal = $(this).val();
        // format
        postal = postal.replace(/^(\d{3})(\d{4})$/, "$1-$2");
        $(this).val(postal);
        // clear
        $('.address_pref').val('');
        $('.address_address1').val('');
        $('.address_address2').val('');

        $('.search-address-error-message').remove();
        searchAddress(postal);
    });
}

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

            var postcode = ad[0].short_name;
            ad.reverse();

            if (ad[0] && ad[0].short_name === 'JP') {
                // 該当あり
                var pref = '';
                var address1 = '';
                var address2 = '';
                for (var index = 1; index < ad.length - 1; index++) {
                    if (index === 1) {
                      pref = ad[index].long_name;
                    }
                    if (index === 2) {
                      address1 = ad[index].long_name;
                    }
                    if (index >= 3) {
                      address2 += ad[index].long_name;
                    }
                }

                $('.address_pref').val(pref);
                $('.address_address1').val(address1);
                $('.address_address2').val(address2);
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

 function dispAddressAdd() {
    if ($('select.select-add-address-pickup').val() !== '-99') {
        $('.input-address').hide();
    } else {
        $('.input-address').show();
        $('#address_id').val('-99');
    }
 }
