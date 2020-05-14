<?php
App::uses('AppModel', 'Model');
App::import('Vendor', 'google_api/vendor/autoload');

class GoogleModel extends AppModel
{
    public function getUserInfo_regist($_array)
    {
        $client = new Google_Client(['client_id' => Configure::read('app.google.id_token')]);
        $payload = $client->verifyIdToken($_array['GoogleUser']['id_token']);
        if ($payload) {
          $_array['GoogleUser']['google_user_id'] = $payload['sub'];
          $_array['GoogleUser']['email'] = $payload['email'];
          $_array['GoogleUser']['firstname'] = $payload['given_name'];
          $_array['GoogleUser']['lastname'] = $payload['family_name'];
        };
        //* Return
        return $_array;
    }

    public function getUserInfo_login($_array)
    {
        $client = new Google_Client(['client_id' => Configure::read('app.google.id_token')]);
        $payload = $client->verifyIdToken($_array['CustomerLoginGoogle']['id_token']);
        if ($payload) {
          $_array['CustomerLoginGoogle']['google_user_id'] = $payload['sub'];
        };
        //* Return
        return $_array;
    }
}
