		$(function () {
			$("#header").load("elements/header.html");
		});
		$(function () {
			$('.nav-hamburger').click(function () {
				$('.nav-menu').slideToggle( 1000 , "easeOutBounce");
			});
		});