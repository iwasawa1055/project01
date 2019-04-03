var AppInboundConfirm =
  {
    init: function() {
        // ボタンを非活性
        $('#execute').css('opacity','0.3');
        $('#execute').prop('disabled',true);

        $('#execute').on('click', function (e) {
            if ($('.agree-before-submit :checked').length == $('.agree-before-submit').length) {
                document.form.submit();
                $('.loader').airCenter();
                $('.airloader-overlay').show();
            }
        });

        $('.agree-before-submit').on('click', function (e) {
            if ($('.agree-before-submit :checked').length == $('.agree-before-submit').length) {
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
    AppInboundConfirm.init();
  });
