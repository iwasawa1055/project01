<?php
App::uses('AppModel', 'Model');
//App::import('Vendor','google_api',array('file' => 'vendor' . DS . 'autoload.php'));
// App::import('Vendor', 'google_api', 'vendor', array('file' => 'autoload.php'));
require_once '../Vendor/google_api/vendor/autoload.php';

class GoogleModel extends AppModel
{
    public function getUserInfo($_array)
    {
        $client = new Google_Client(['client_id' => '56091862582-mljt29dmcdgcj1fojhaqqpom9ud4mige.apps.googleusercontent.com']);
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
}
