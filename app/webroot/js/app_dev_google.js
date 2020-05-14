
function onLoadCallback() {
  console.log('onLoadCallback');
  gapi.load('auth2', function() {
    gapi.auth2.init({
        client_id: '56091862582-mljt29dmcdgcj1fojhaqqpom9ud4mige.apps.googleusercontent.com',
        fetch_basic_profile: false,
        scope: 'email profile openid'
    });
  });
}
function signIn() {
    console.log('signIn');
    var auth2 = gapi.auth2.getAuthInstance();
      auth2.signIn().then(function() {
        console.log(auth2.currentUser.get().getId());
        console.log(auth2.currentUser.get().Pt.yu); //email
        console.log(auth2.currentUser.get().Pt.CU); //苗字
        console.log(auth2.currentUser.get().Pt.BW); //名前
        console.log(auth2.currentUser.get().tc.access_token);
        console.log(auth2.currentUser.get().tc.id_token);
        $('#dev_id_google_registform input[name="data[GoogleUser][access_token]"]').val(auth2.currentUser.get().tc.access_token);
        $('#dev_id_google_registform input[name="data[GoogleUser][id_token]"]').val(auth2.currentUser.get().tc.id_token);
        $("#dev_id_google_registform").submit();
      });
}

