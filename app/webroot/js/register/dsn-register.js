$(function() {
    /**
     $("#header").load("/elements/header.html");
     $("#footer").load("/elements/footer.html");
     **/
});

$(function() {
    $('.btn-starter').click(function() {
        $('.btn-starter').toggleClass("active");
    });
});
$(function() {
    var ua = navigator.userAgent;
    if (ua.indexOf('Android') > 0) {
        $('.focused').focus(function() {
            $('#header').hide();
            //$('#header').fadeOut();
        });
        $('.focused').blur(function() {
            $('#header').show();
            //$('.nextback').show();
        });
        $('input[type="radio"]').change(function() {
            $('#header').hide();
            //$('.nextback').hide();
        });
    }
});

$(function() {
    var valueStep = 1;
    var minValue = 1;
    var maxValue = 100;
    var spinnerValue = $(".input-spinner").val();
    var spinnerValue = parseInt(spinnerValue);

    $('.spinner-down').on('click', function() {
        $(this).next('.input-spinner').val(spinnerValue -= valueStep);
        if (spinnerValue < minValue) {
            spinnerValue = minValue;
        }
    });

    $('.spinner-up').on('click', function() {
        $(this).prev('.input-spinner').val(spinnerValue += valueStep);
        if (spinnerValue > maxValue) {
            spinnerValue = maxValue;
        }
    });
    return false;
});


$(function() {
    $('#cap-ha , #cap-hb').hide();
    $('[name=select-hako]').change(function() {
        if ($(this).val() === 'hako-regular') {
            $(this).parent().parent().next().children('#cap-hr').slideDown('fast');
            $(this).parent().parent().next().children().not('#cap-hr').hide();
        }
        if ($(this).val() === 'hako-apparel') {
            $(this).parent().parent().next().children('#cap-ha').slideDown('fast');
            $(this).parent().parent().next().children().not('#cap-ha').hide();

        }
        if ($(this).val() === 'hako-book') {
            $(this).parent().parent().next().children('#cap-hb').slideDown('fast');
            $(this).parent().parent().next().children().not('#cap-hb').hide();
        }
    });
    return false;
});

$(function() {
    $('#cap-ma , #cap-dr, #cap-da').hide();
    $('[name=select-mono]').change(function() {
        if ($(this).val() === 'mono-regular') {
            $(this).parent().parent().next().children('#cap-mr').slideDown('fast');
            $(this).parent().parent().next().children().not('#cap-mr').hide();
        }
        if ($(this).val() === 'mono-apparel') {
            $(this).parent().parent().next().children('#cap-ma').slideDown('fast');
            $(this).parent().parent().next().children().not('#cap-ma').hide();

        }
        if ($(this).val() === 'dash-regular') {
            $(this).parent().parent().next().children('#cap-dr').slideDown('fast');
            $(this).parent().parent().next().children().not('#cap-dr').hide();
        }
        if ($(this).val() === 'dash-apparel') {
            $(this).parent().parent().next().children('#cap-da').slideDown('fast');
            $(this).parent().parent().next().children().not('#cap-da').hide();

        }
    });
    return false;
});