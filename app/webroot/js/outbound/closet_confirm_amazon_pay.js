var AppOutboundClosetConfirm =
  {
    init: function() {
        // ボタンを非活性
        $('#execute').css('opacity','0.3');
        $('#execute').prop('disabled',true);

        $('#execute').on('click', function (e) {
            if ($('.input-check :checked').length == $('.input-check').length) {
                window.location.href = '/outbound/closet_complete_amazon_pay'
                $('.loader').airCenter();
                $('.airloader-overlay').show();
            }
        });

        $('.input-check').on('click', function (e) {
            if ($('.input-check :checked').length == $('.input-check').length) {
                // ボタンを活性
                $('#execute').css('opacity','1');
                $('#execute').prop('disabled',false);
            } else {
                // ボタンを非活性
                $('#execute').css('opacity','0.3');
                $('#execute').prop('disabled',false);
            }
        });
    },
  }

/*
 * document ready
 * */
$(function()
  {
    AppOutboundClosetConfirm.init();
  });
