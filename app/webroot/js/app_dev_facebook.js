var Facebook = {

    _ : function() {
        $(".dev_facebook_login").click(function(){
            FB.login(function(response) {
                if (response.status === 'connected') {
                    Facebook.statusChangeCallbackForLogin(response);
                    return false;
                }
            });
        });
        $(".dev_facebook_regist").click(function(){
            FB.login(
                function(response){
                    Facebook.statusChangeCallbackForRegist(response);
                    return false;
                },
                {scope: 'public_profile,email'}
            );
        });
    },
    statusChangeCallbackForRegist: function (response) {

        var obj_authinfo = {
            accessToken : response.authResponse.accessToken,
            userId      : response.authResponse.userID,
        };

        FB.api('/me', {fields: 'email,name,first_name,last_name,gender,birthday,location'}, function(response) {
            if (response.email == undefined) {
                alert('we can’t regist an account without email. please allow our application to send email on facebook.');
                return false;
            }

            $('#dev_id_facebook_registform input[name="data[CustomerRegistInfo][access_token]"]').val(obj_authinfo.accessToken);
            $('#dev_id_facebook_registform input[name="data[CustomerRegistInfo][facebook_user_id]"]').val(obj_authinfo.userId);
            $('#dev_id_facebook_registform input[name="data[CustomerRegistInfo][email]"]').val(response.email);
            $('#dev_id_facebook_registform input[name="data[CustomerRegistInfo][firstname]"]').val(response.first_name);
            $('#dev_id_facebook_registform input[name="data[CustomerRegistInfo][lastname]"]').val(response.last_name);
            // TODO facebookへ申請をする必要あり
            // $('#dev_id_facebook_registform input[name="facebook_gender"]').val(response.gender);
            // $('#dev_id_facebook_registform input[name="facebook_birthday"]').val(response.birthday);
            // $('#dev_id_facebook_registform input[name="facebook_location"]').val(response.location);

            $("#dev_id_facebook_registform").submit();

        });
    },
    statusChangeCallbackForLogin: function (response) {
        if ( response.status !== "connected" ) {
            return false;
        }

        var obj_authinfo = {
            accessToken : response.authResponse.accessToken,
            userId      : response.authResponse.userID,
        };

        FB.api('/me', {fields: 'email'}, function(response) {
            $('#dev_id_facebook_loginform input[name="data[CustomerLoginFacebook][facebook_user_id]"]').val(obj_authinfo.userId);
            $('#dev_id_facebook_loginform input[name="data[CustomerLoginFacebook][access_token]"]').val(obj_authinfo.accessToken);
            $("#dev_id_facebook_loginform").submit();

        });
    }
}


$(function()
{
    Facebook._();
});
