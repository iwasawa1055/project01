$(function () {
	$('.btn-option').click(function () {
		$('.item-sort').toggle('fast');
		$('.btn-option').toggleClass("active");
	});

	$('.btn-check').click(function () {
		$('.btn-check').toggleClass("active");
	});

	$('#item_confirm').click(function() { 
		$("#itemlist").submit();
	});

	$('.item-select input[type=checkbox]').change(function(){
		var price = parseInt($(this).data("price"));
		var totalprice = $("#block_selected_price").text();
		var totalselect = parseInt($("#block_selected_item").text());
		
		totalprice = parseInt(totalprice.replace(/\,/,""));
		
		if ( $(this).is(':checked')) {
			totalprice += price;
			totalselect++;
		} else {
			totalprice -= price;
			totalselect--;
		}
		
		$("#block_selected_price").text(totalprice.toLocaleString());
		$("#block_selected_item").text(totalselect);
	});
});



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