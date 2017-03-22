		$(function () {
			/*$("#header").load("/elements/header.html");*/
			/**$("#footer").load("/elements/footer.html");**/
		});
		$(function () {
			$('input[name="select-card"]#as-card').change(function () {
				$('.dsn-input-security-code').toggle('fast');
				$('.dsn-input-change-card').hide('fast');
				$('.dsn-input-new-card').hide('fast');
			});
		});
		$(function () {
			$('input[name="select-card"]#change-card').change(function () {
				$('.dsn-input-change-card').toggle('fast');
				$('.dsn-input-security-code').hide('fast');
				$('.dsn-input-new-card').hide('fast');
			});
		});
		$(function () {
			$('input[name="select-card"]#new-card').change(function () {
				$('.dsn-input-new-card').toggle('fast');
				$('.dsn-input-security-code').hide('fast');
				$('.dsn-input-change-card').hide('fast');
			});
		});
		$(function () {
			$('.dsn-new-adress').click(function () {
				$('.dsn-input-new-adress').toggle('fast');
			});
		});