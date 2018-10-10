var AppAmazonPayComplete =
{
    a: function () {
        $('#AmazonPayLogoutButton').on('click', function (e) {
            amazon.Login.logout();
        });
    }
};
/*
 * document ready
 * */
$(function()
{
    AppAmazonPayComplete.a();
});
