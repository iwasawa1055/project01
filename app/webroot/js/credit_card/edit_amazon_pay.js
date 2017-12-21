var AppEditAmazonPay =
{
    a: function () {
        $('#amazonPayLogout').on('click', function (e) {
            amazon.Login.logout();
            location.href = '/login/logout';
        });
    }
}
/*
 * document ready
 * */
$(function()
{
  AppEditAmazonPay.a();
});
