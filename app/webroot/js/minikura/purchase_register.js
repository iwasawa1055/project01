$(function() {

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

    getDatetime();
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

  function getDatetime() {
    var elem_postal = $('#CustomerInfoPostal');
    var elem_datetime = $('#CustomerInfoDatetimeCd');

    if (!elem_postal.val()) {
      elem_datetime.empty();
      return;
    }

    $('option:first', elem_datetime).prop('selected', true);
    elem_datetime.attr("disabled", "disabled");

    $.post('/purchase/entry_register/getAddressDatetime',
      { postal: elem_postal.val() },
      function(data){
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

  $('.date_zero_padding').blur(function() {
    var val = $(this).val();
    if (val.match(/^\d+$/) === null) {
        return;
    }
    $(this).val(('0' + val).slice(-2));
  });

  $('.agree-before-submit[type="checkbox"]').click(checkAgreeBeforeSubmit);
  checkAgreeBeforeSubmit();

  function checkAgreeBeforeSubmit() {
    var count = $('.agree-before-submit[type="checkbox"]').length;
    if (0 < count) {
      $('.container button[type=submit]').attr('disabled', 'true');
      if (count === $('.agree-before-submit[type="checkbox"]:checked').length) {
        $('.container button[type=submit]').attr('disabled', null);
      }
    }
  }

});
