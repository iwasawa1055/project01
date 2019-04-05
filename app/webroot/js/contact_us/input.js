var AppInputDetail =
{
  a: function () {
    $("#ZendeskContactUsComment").each(function(){
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
            url      : '/contact_us/as_get_contact_help',
            cache    : false,
            data     : data,
            dataType : 'json',
            type     : 'POST'
          }).done(function (data, textStatus, jqXHR) {
            $('#help_link a').remove();
            $.each(data.result, function (index, data) {
              if (index < 5) {
                $('#help_link').append('<p><a href="' + data['html_url'] + '" target="_blank">' + data['title'] + '</a></p>');
              }
            });
          }).fail(function (data, textStatus, errorThrown) {
            console.log('失敗');
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

