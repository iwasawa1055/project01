var AppInboundBaseBoxAdd =
{
  init: function() {
    // ボックスタイプ別に出力する内容を切り替える
    $('[name="data[InboundBase][box_type]"]').change(function() {
      if ($(this).val() === 'new') {
        $('#dev-new-box').fadeIn(400, 'easeInOutExpo');
        $('#dev-old-box').fadeOut(400, 'easeInOutExpo');
        $("#dev-self-delivery").show();
        $("#dev_collect").show();
        $("#dev_self").hide();
      } else if ($(this).val() === 'old') {
        $("input[name='data[InboundBase][delivery_carrier]']:eq(0)").prop('checked', true);
        $("#dev-self-delivery").hide();
        $("#dev_collect").show();
        $("#dev_self").hide();
        $('#dev-new-box').fadeOut(400, 'easeInOutExpo');
        $('#dev-old-box').fadeIn(400, 'easeInOutExpo');
      }
    });
  },

  submitForm: function() {
    $("#execute").click("on", function(){
      document.form.submit();
    });
    return new $.Deferred().resolve().promise();
  },
}

/*
 * document ready
 * */
$(function()
{
  AppInboundBaseBoxAdd.init();
  AppInboundBaseBoxAdd.submitForm();
});
