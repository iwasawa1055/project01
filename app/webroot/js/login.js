var AppAmazonPayLogin =
{
    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    IsAmazonLoginButton:false,
    TimerID:null,
    a: function () {
        window.onAmazonLoginReady = function(){
            amazon.Login.setClientId(AppAmazonPayLogin.ClientId);
        };
        window.onAmazonPaymentsReady = function() {
            // onAmazonLoginReadyに来ない場合がある
            amazon.Login.setClientId(AppAmazonPayLogin.ClientId);

            // Render the button here.
            AppAmazonPayLogin.c("AmazonPayButtonLogin","/login/login_by_amazon_pay");
        };
    },
    b: function () {
        document.getElementById('Logout').onclick = function() {
            console.log('logout');
            amazon.Login.logout();
        };
    },
    c: function (button_name, path) {
        var authRequest;
        var host = location.protocol + '//' + location.hostname;
        OffAmazonPayments.Button(button_name, AppAmazonPayLogin.SELLER_ID, {
          type: "LwA",
          color: "Gold",
          size: "medium",
          authorization: function () {
            loginOptions = {scope: "profile payments:widget  payments:shipping_address", popup: "true"};
            authRequest = amazon.Login.authorize(loginOptions, host + path);
          }
        });
        // amazon.Login.logout();
    },
    // ログイン時にボタンが表示されない問題のリカバリー処理
    d: function () {
        if($("#AmazonPayButtonLogin").children().size() === 0) {
            AppAmazonPayLogin.a();
        } else {
            AppAmazonPayLogin.IsAmazonLoginButton = true;
            clearInterval(AppAmazonPayLogin.TimerID);
        };
    },
    e: function () {
        AppAmazonPayLogin.TimerID = setInterval(function(){
            if (AppAmazonPayLogin.IsAmazonLoginButton === false) {
                AppAmazonPayLogin.d();
            }
        },1000);
    }
}

/*
 * document ready
 */
AppAmazonPayLogin.a();
AppAmazonPayLogin.e();
