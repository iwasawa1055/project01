$("#arrival").click(function () {
	$('.dsn-arrival').show('fast');
	$('.dsn-yamato').hide('fast');
});
$("#yamato").click(function () {
	$('.dsn-arrival').hide('fast');
	$('.dsn-yamato').show('fast');
});