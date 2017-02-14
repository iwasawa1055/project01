var AppDev =
{
	a: function () {
		// スターターキットボックス選択
		$('.btn-starter').click(function () {
			$('.btn-starter').toggleClass("active");
			if( $('.btn-starter').hasClass(("active"))) {
				$('.select-number').html('<span>1セット選択済み</span>');
				$('#select_starter_kit').val(1);
			} else {
				$('.select-number').html('未選択');
				$('#select_starter_kit').val(0);
			}
		});},
}

/*
 * document ready
 * */
$(function()
{
	AppDev.a();
	//AppDev.b();
});
