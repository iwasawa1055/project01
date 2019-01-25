var AppOutboundLibraryConfirm =
  {
    init: function() {
        $('#execute').on('click', function (e) {
            if ($('.input-check :checked').length == $('.input-check').length) {
                window.location.href = '/outbound/library_complete_amazon_pay'
                $('.loader').airCenter();
                $('.airloader-overlay').show();
            } else {
                $('#check-error').html('<p style="color:red">※確認いただき、チェックを付けてください。</p>');
            }
        });
    },
  }

/*
 * document ready
 * */
$(function()
  {
    AppOutboundLibraryConfirm.init();
  });
