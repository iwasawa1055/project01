
console.log('test');


function onLoadCallback() {
  console.log('onLoadCallback');
  gapi.load('auth2', function() {
    gapi.auth2.init({
        client_id: '56091862582-mljt29dmcdgcj1fojhaqqpom9ud4mige.apps.googleusercontent.com',
        fetch_basic_profile: false,
        scope: 'email profile openid',
    });
  });
}
// 登録用 
function signIn() {
    console.log('signIn.regist');
    var auth2 = gapi.auth2.getAuthInstance();
      auth2.signIn().then(function() {
        console.log(auth2.currentUser.get().getAuthResponse().id_token);
        console.log(auth2.currentUser.get().getAuthResponse().access_token);
        $('#dev_id_google_registform input[name="data[GoogleUser][access_token]"]').val(auth2.currentUser.get().getAuthResponse().access_token);
        $('#dev_id_google_registform input[name="data[GoogleUser][id_token]"]').val(auth2.currentUser.get().getAuthResponse().id_token);
        $("#dev_id_google_registform").submit();
      });
}
// ログイン用
function Login() {
    console.log('signIn.login');
    var auth2 = gapi.auth2.getAuthInstance();
      auth2.signIn().then(function() {
        console.log(auth2.currentUser.get().getAuthResponse().id_token);
        console.log(auth2.currentUser.get().getAuthResponse().access_token);
        $('#dev_id_google_loginform input[name="data[CustomerLoginGoogle][access_token]"]').val(auth2.currentUser.get().getAuthResponse().access_token);
        $('#dev_id_google_loginform input[name="data[CustomerLoginGoogle][id_token]"]').val(auth2.currentUser.get().getAuthResponse().id_token);
        $("#dev_id_google_loginform").submit();
      });
}


