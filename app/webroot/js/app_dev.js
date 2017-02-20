// animsition
$(function() {
  Act._();
  Act.a();

  $('a[href^="/"]a[target!="_blank"]').addClass('animsition-link');
  $('button[type=submit]').addClass('page-transition-link');

  $('.agree-before-submit[type="checkbox"]').click(checkAgreeBeforeSubmit);
  checkAgreeBeforeSubmit();

  $('form').submit(function() {
    $('button[type=submit]', this).attr('disabled', 'true');
    if ($('button[type=submit]', this).hasClass('submit_after_restore')) {
      setTimeout(function() {
        $('button.submit_after_restore').attr('disabled', false);
      }, 1000);
    }
  });

  $('select.select-add-address').change(function() {
    if ($(this).val() == '-99') {
      $('form.select-add-address-form').submit();
    }
  });
});

function checkAgreeBeforeSubmit() {
  var count = $('.agree-before-submit[type="checkbox"]').length;
  if (0 < count) {
    $('#page-wrapper button[type=submit], #js-agreement_on_page button[type=submit]').attr('disabled', 'true');
    if (count === $('.agree-before-submit[type="checkbox"]:checked').length) {
      $('#page-wrapper button[type=submit], #js-agreement_on_page button[type=submit]').attr('disabled', null);
    }
  }
}


// change contact form 
$(function () {
  $("#InquiryDivision").change(function () {
      inquiryDivision = $("#InquiryDivision").val();
      if (inquiryDivision === '15') {
          $("#inquiry_bug_area").show();
      } else {
          $("#inquiry_bug_area").hide();
      }
  });
});

// contact form default
$(function () {
    inquiryDivision = $("#InquiryDivision").val();
    if (inquiryDivision === '15') {
        $("#inquiry_bug_area").show();
    } else {
        $("#inquiry_bug_area").hide();
    }
});


// change contact form 
$(function () {
  $("#ContactUsDivision").change(function () {
      contactUsDivision = $("#ContactUsDivision").val();
      if (contactUsDivision === '15') {
          $("#bug_area").show();
      } else {
          $("#bug_area").hide();
      }
  });
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

var Act =
{
    // フェイドイン
    _: function()
    {
        // $('body').css({'display': 'none'}).fadeIn(1000);
    },

    // クリックワンス & ローダー出力
    a: function()
    {
        $('body').on('click', '.animsition-link, .page-transition-link', function()
        {
            if ($(this).prop('href') === 'javascript:void(0)') {
                return false;
            }

            $('.loader').airCenter();
            $('.airloader-overlay').show();
        });
    },
};
