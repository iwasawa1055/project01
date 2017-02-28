		$(function () {
			$("#header").load("/elements/header.html");
			/**$("#footer").load("/elements/footer.html");**/
		});
		$(function () {
			$('.btn-starter').click(function () {
				$('.btn-starter').toggleClass("active");
			});
		});
		$(function () {
			var ua = navigator.userAgent;
			if (ua.indexOf('Android') > 0) {
				$('.focused').focus(function () {
					$('#header').hide();
					//$('#header').fadeOut();
				});
				$('.focused').blur(function () {
					$('#header').show();
					//$('.nextback').show();
				});
				$('input[type="radio"]').change(function() {
					$('#header').hide();
					//$('.nextback').hide();
				});
			}
		});
