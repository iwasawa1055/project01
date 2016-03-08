$(function() {
    $('.date_zero_padding').blur(function() {
        var val = $(this).val();
        if (val.match(/^\d+$/) === null) {
            return;
        }
        $(this).val(('0' + val).slice(-2));
    });
});
