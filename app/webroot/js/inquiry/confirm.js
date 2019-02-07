var AppInquiryConfirm =
    {
        a: function () {
            //ボタンの色を薄くする
            $('#execute').css("opacity", "0.5");

            $('#terms').on('click', function(){
                if ($('#terms').prop('checked')) {
                    $('#execute').css("opacity", "1");
                } else {
                    $('#execute').css("opacity", "0.5");
                }
            });

            $('#execute').on('click', function(){
                if ($('#terms').prop('checked')) {
                    $('#ZendeskInquiryAddForm').submit();
                }
            });
        },
    }

$(function()
{
    AppInquiryConfirm.a();
});