		$(function () {
			/*$("#header").load("/elements/header.html");*/
			/**$("#footer").load("/elements/footer.html");**/
		});
		$("#arrival").click(function () {
			$('.dsn-arrival').show('fast');
			$('.dsn-yamato').hide('fast');
		});
		$("#yamato").click(function () {
			$('.dsn-arrival').hide('fast');
			$('.dsn-yamato').show('fast');
		});
		$(".dsn-btn-signin").click(function () {
			$('#dsn-signin').slideToggle('fast');
			$('#dsn-amazon').hide('fast');
			$('.dsn-btn-amazon').toggleClass('dsn-disable');
			$('.dsn-btn-signin').removeClass('dsn-disable');
		});
		$(".dsn-btn-amazon").click(function () {
			$('#dsn-amazon').slideToggle('fast');
			$('#dsn-signin').hide('fast');
			$('.dsn-btn-signin').toggleClass('dsn-disable');
			$('.dsn-btn-amazon').removeClass('dsn-disable');
		});

		$(function () {
			$("[data-scroll]").click(function () {
				 var speed = 200,
					$self = $(this),
					$href = $self.attr('href'),
					$margin = $self.attr('data-scroll') ? parseInt($self.attr('data-scroll')) : 0,
					$target = $($href);
				var pos = ($target[0] && $target !== '#page_top') ? $target.offset().top - $margin : 0;
				$('html,body').animate({
					scrollTop: pos
				}, speed, 'swing');
				$self.blur();
				return false;
			});
		});
