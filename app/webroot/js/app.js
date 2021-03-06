var Speed = 400;
var Ease = 'easeInOutExpo';
//
$(function() {
    $('#side-menu').metisMenu();
});

$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').addClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});


$(function() {
    $('.input-address').hide();
    $('[name=address]').change(function() {
        $('.input-address').hide();
        if ($(this).val() === 'add') {
            $('.input-address').slideDown(Speed, Ease);
        } else {
            $('.input-address').slideUp(Speed, Ease);
        }
    });
    return false;
});

$(function() {
    $('.inline-description').hide();
    $('.title-description').click(function() {
        $(this).next('.inline-description').slideToggle(Speed, Ease);
    });
    return false;
});

$(function() {
    $('#existing').hide();
    $('[name=select-box]').change(function() {
        if ($(this).val() === 'new') {
            $('#new').fadeIn(Speed, Ease);
            $('#existing').fadeOut(Speed, Ease);
        } else if ($(this).val() === 'existing') {
            $('#new').fadeOut(Speed, Ease);
            $('#existing').fadeIn(Speed, Ease);
        }
    });
    return false;
});


$(function() {
    var valueStep = 1;
    var minValue  = 0;
    var maxValue  = 20;
    $('.btn-spinner').on('click', function() {
        var itemValue  = parseInt($(this).parents('.spinner').find('.input-spinner').val());
        var totalValue = parseInt($(this).parents('.lineup-caption').find('p[class=select-num]').children('span').text());
        var btnType  = $(this).attr('name');
        if (btnType === 'spinner_down') {
            if (itemValue > minValue) {
                $(this).parents('.spinner').find('.input-spinner').val(itemValue-valueStep);
                $(this).parents('.lineup-caption').find('p[class=select-num]').children('span').text(totalValue-valueStep);
            }
        }
        if (btnType === 'spinner_up') {
            if (itemValue < maxValue) {
                $(this).parents('.spinner').find('.input-spinner').val(itemValue+valueStep);
                $(this).parents('.lineup-caption').find('p[class=select-num]').children('span').text(totalValue+valueStep);
            }
        }
    });
    return false;
});

$(function() {
    $('.captions').hide();
    $('.view-caption').click(function() {
        $(this).parent().next().next('.captions').slideToggle(Speed, Ease);
    });
    return false;
});

$(function() {
    $('.captions').hide();
    $('.application').click(function() {
        $(this).next('.captions').slideToggle(Speed, Ease);
    });
    return false;
});

$(function() {
    var pms = $('.price-ms');
    var pmb = $('.price-mb');
    var sDash = $('.select-dash');
    var dash = '999';
    var normal = '250';
    $('.dash-caption').hide();
    sDash.hide();
    $('[name=select-dash]').change(function() {
        if ($('input[name=select-dash]:eq(0)').prop('checked')) {
            $('.dash-caption').slideDown(Speed, Ease);
            var mb = pmb.text().replace(normal, dash);
            pmb.text(mb);
            var ms = pms.text().replace(normal, dash);
            pms.text(ms);
            sDash.fadeIn(Speed, Ease);

        } else {
            $('.dash-caption').slideUp(Speed, Ease);
            var mb = pmb.text().replace(dash, normal);
            pmb.text(mb);
            var ms = pms.text().replace(dash, normal);
            pms.text(ms);
            sDash.fadeOut(Speed, Ease);
        }
    });
    return false;
});

$(function() {
    $('[name=sns]').change(function() {
        if ($(this).val() === 'link') {
            window.location.href = 'fb-login.php';
        } else {}
    });
    return false;
});

$(function() {
    var cc = $('#input-cc');
    var sc = $('#input-sc');
    var nc = $('#input-nc');
    cc.hide();
    nc.hide();
    //
    $('[name=select-card]').change(function() {
        if ($(this).val() === 'as-card') {
            sc.slideDown(Speed, Ease);
            cc.slideUp(Speed, Ease);
            nc.slideUp(Speed, Ease);
        }
        if ($(this).val() === 'change-card') {
            cc.slideDown(Speed, Ease);
            sc.slideUp(Speed, Ease);
            nc.slideUp(Speed, Ease);
        }
        if ($(this).val() === 'new-card') {
            nc.slideDown(Speed, Ease);
            sc.slideUp(Speed, Ease);
            cc.slideUp(Speed, Ease);
        }
    });
    return false;
});

$(function() {
    $('.box-input-name ,[name=remove-package]').prop('disabled', true);
    $('.remove-package').addClass('input-disabled');
    $('[name=is-item-checked]').change(function() {
        if ($(this).prop('checked')) {
            $(this).parent().next().children('.box-input-name').addClass('item-checked').prop('disabled', false);
            $(this).parent().next().children('.remove-package').removeClass('input-disabled').children('[name=remove-package]').prop('disabled', false);
        } else {
            $(this).parent().next().children('.box-input-name').removeClass('item-checked').prop('disabled', true);
            $(this).parent().next().children('.remove-package').addClass('input-disabled').children('[name=remove-package]').prop('disabled', true);;

        }
    });
    return false;
});

$(function () {
    $('#sort-item').hide();
    $('[name=view-sort]').change(function () {
        if ($(this).is(':checked')) {
            $('#sort-item').slideDown(300);
        } else {
            $('#sort-item').slideUp(300);
        }
    });
    return false;
});


$(function () {

    $('.l-guide-blk').hide();
    $('[name=guide]').change(function () {

        if ($('input[name=guide]:eq(0)').prop('checked')) {
            $('#tgl-ctt1').slideDown(300);
            $('#tgl-ctt2,#tgl-ctt3').slideUp(300);


        }
        if ($('input[name=guide]:eq(1)').prop('checked')) {
            $('#tgl-ctt2').slideDown(300);
            $('#tgl-ctt1,#tgl-ctt3').slideUp(300);

        }
        if ($('input[name=guide]:eq(2)').prop('checked')) {
            $('#tgl-ctt3').slideDown(300);
            $('#tgl-ctt1,#tgl-ctt2').slideUp(300);
        }

    });
    return false;
});
$(function () {
    $('.toggle-close').click(function () {
        $(this).parent('.l-guide-blk').slideUp(300);
        $('input[name=guide]').prop('checked', false);
    });
    return false;
});

$(function () {
    $('.l-slide-down').hide();
    $('.link-slide').click(function () {
        $(this).next('.l-slide-down').slideToggle(Speed, Ease);
    });
    return false;
});


$(function () {

    $('#collect,#self').hide();
    $('[name=delivery-method]').change(function () {

        if ($('[name=delivery-method]:eq(0)').prop('checked')) {
            $('#collect').slideDown(300);
            $('#self').slideUp(300);
        }
        if ($('[name=delivery-method]:eq(1)').prop('checked')) {
            $('#self').slideDown(300);
            $('#collect').slideUp(300);
        }
    });
    return false;
});

$(function () {
    $(window).on('load', function () {
        $('.check').attr("class", "start");
    });
    return false;
});

$('.guidance').iziModal({
    group: "group",
    zindex: 100000,
    radius: 5,
    width: 550,
    focusInput: false,
    arrowKeys: false,
    loop: false,
    arrowKeys: false,
    navigateCaption: false,
    navigateArrows: true,
    overlayColor: 'rgba(0, 0, 0, 0.5)',
    overlayClose: false,
});

$(function () {
    $('#btn-add').addClass('btn-disabled');
    $('[name=chk-agree]').change(function () {
        if ($('[name=chk-agree]:eq(0)').prop('checked')) {
            $('#btn-add').removeClass('btn-disabled');
        } else {
            $('#btn-add').addClass('btn-disabled');
        }
    });
    return false;
});
