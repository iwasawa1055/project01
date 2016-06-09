$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
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
    var element = $('.sidebar-nav ul.nav a').filter(function() {
        return (this.href == url.href) || (this.pathname != '/' && url.href.indexOf(this.href) == 0);
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});


// change contact form 
$("#InquiryDivision").change(function () {
    inquiryDivision = $("#InquiryDivision").val();
    if (inquiryDivision === '15') {
        $("#bug_area").show();
    } else {
        $("#bug_area").hide();
    }
});

// contact form default
$(function () {
    inquiryDivision = $("#InquiryDivision").val();
    if (inquiryDivision === '15') {
        $("#bug_area").show();
    } else {
        $("#bug_area").hide();
    }
});


// change contact form 
$("#ContactUsDivision").change(function () {
    contactUsDivision = $("#ContactUsDivision").val();
    if (contactUsDivision === '15') {
        $("#bug_area").show();
    } else {
        $("#bug_area").hide();
    }
});

// contact form default
$(function () {
    contactUsDivision = $("#ContactUsDivision").val();
    if (contactUsDivision === '15') {
        $("#bug_area").show();
    } else {
        $("#bug_area").hide();
    }
});

