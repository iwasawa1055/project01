$(function() {

  $('#PaymentGMOPurchaseAddressId').change(function() {
      getDatetime();
  });

  function getDatetime() {
    var elem_address = $('#PaymentGMOPurchaseAddressId');
    var elem_datetime = $('#PaymentGMOPurchaseDatetimeCd');

    // 未選択また「追加」を選択
    if (!elem_address.val() || elem_address.val() == -99) {
      elem_datetime.empty();
      return;
    }

    $('option:first', elem_datetime).prop('selected', true);
    elem_datetime.attr("disabled", "disabled");

    $.post('/purchase/getAddressDatetime',
      { address_id: elem_address.val() },
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
