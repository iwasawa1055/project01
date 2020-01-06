var AppInputDetail =
{
  a: function () {
    $("#ZendeskInquiryComment").each(function(){
      $(this).bind('keyup', contact_target(this));
    });
    function contact_target(elm){
      var v, old = elm.value;
      return function(){
        if(old != (v=elm.value)){
          old = v;
          // 引数取得
          var data = {
            "contact_message" : $(this).val()
          };
          $.ajax({
            url      : '/inquiry/as_get_contact_help',
            cache    : false,
            data     : data,
            dataType : 'json',
            type     : 'POST'
          }).done(function (data, textStatus, jqXHR) {
            $('.ls-help li').remove();
            if (data.result.length === 0) {
              $('.ls-help').append('<li class="l-dtl"><a class="lnk-txt" href="https://help.minikura.com/hc/ja" target="_blank">よくあるご質問はこちら</a></li>');
            } else {
              $.each(data.result, function (index, data) {
                if (index < 5) {
                  $('.ls-help').append('<li class="l-dtl"><a class="lnk-txt" href="' + data['html_url'] + '" target="_blank">' + data['title'] + '</a></li>');
                }
              });
            }
          }).fail(function (data, textStatus, errorThrown) {
          }).always(function (data, textStatus, returnedObject) {
          });
        }
      }
    }
  },
}

/*
 * document ready
 * */
$(function()
{
  AppInputDetail.a();
});

