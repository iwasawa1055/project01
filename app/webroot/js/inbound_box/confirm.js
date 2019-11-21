var AppInboundConfirm =
  {
    init: function() {
        // ボタンを非活性
        $('#execute').css('opacity','0.3');
        $('#execute').prop('disabled',true);

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
    execute: function() {
        $('#execute').on('click', function (e) {
            if ($('[name="data[InboundBase][keeping_type]"]').length > 0) {
                if ($('[name="data[InboundBase][keeping_type]"]:checked').length == 0) {
                    if($('#cleaning-error').length == 0) {
                        $('.cleaning > .headline').after('<p class="valid-il" id="cleaning-error">保管方法を選択してください。</p>')
                    }
                    var valid = $(".valid-il").get(0);
                    if (valid) {
                      var position = valid.offsetTop;
                      $('body,html').animate({scrollTop: position}, 'slow');
                    }
                    return false;
                }
            }
            if ($('.agree-before-submit :checked').length == $('.agree-before-submit').length) {
                document.form.submit();
                $('.loader').airCenter();
                $('.airloader-overlay').show();
            }
        });
    }
  }

/*
 * document ready
 * */
$(function()
  {
    AppInboundConfirm.init();
    AppInboundConfirm.execute();
  });
