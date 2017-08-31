var AppAmazonPayLogin =
{
    SELLER_ID:"A1MBRBB8GPQFL9",
    ClientId:'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e',
    a: function () {
        window.onAmazonLoginReady = function(){
            amazon.Login.setClientId(AppAmazonPayLogin.ClientId);
        };
        window.onAmazonPaymentsReady = function() {
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
          type: "PwA",
          color: "Gold",
          size: "medium",
          authorization: function () {
            loginOptions = {scope: "profile payments:widget  payments:shipping_address", popup: "true"};
            authRequest = amazon.Login.authorize(loginOptions, host + path);
          }
        });
        amazon.Login.logout();
    }
}


/*
 * document ready
 */
 AppAmazonPayLogin.a();
