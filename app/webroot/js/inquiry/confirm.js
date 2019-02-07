var AppInquiryConfirm =
    {
        a: function () {
            //ボタンの色を薄くする
            $('#execute').css("opacity", "0.3");
            $('#execute').prop('disabled',true);

            $('.agree-before-submit').on('click', function(){
                if ($('.agree-before-submit:checked').length == $('.agree-before-submit').length) {
                    $('#execute').css("opacity", "1");
                    $('#execute').prop('disabled',false);
                } else {
                    $('#execute').css("opacity", "0.5");
                    $('#execute').prop('disabled',true);
                }
            });

            $('#execute').on('click', function(){
                if ($('.agree-before-submit:checked').length == $('.agree-before-submit').length) {
                    $('#ZendeskInquiryConfirmForm').submit();
                }
            });
        },
    }

$(function()
{
    AppInquiryConfirm.a();
});
