var AppAmazonPayLoginDev =
{
    // jqueryの後に読み込みたいので、login.jsと分割をしています。
    a: function () {
        if($(".dsn-amazon-login").children('span').hasClass('validation')) {
             window.onAmazonLoginReady = function(){
                 console.log('amazon logout');
                 amazon.Login.logout();
             };
        };
    }
}

$(function(){
    AppAmazonPayLoginDev.a();
});
