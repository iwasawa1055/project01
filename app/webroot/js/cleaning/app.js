			$(function () {
				$('.btn-option').click(function () {
					$('.item-sort').toggle('fast');
					$('.btn-option').toggleClass("active");
				});
			});
			//$(function () {
			//	$('.btn-check').click(function () {
			//		$('.btn-check').toggleClass("active");
			//	});
			//});
			$(function () {
				var $body = $('body'),
					$navCleaning = $('.nav-cleaning'),
					navCleaningOffsetTop = $navCleaning.offset().top;

				$(window).on('scroll', function () {
					if ($(this).scrollTop() > navCleaningOffsetTop) {
						$body.addClass('fixed');
					} else {
						$body.removeClass('fixed');
					}
				});
			});