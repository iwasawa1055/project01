<?php
// Manual Configure & Startup Configure
$config['site.static_content_url'] = 'https://minikura.com';
$config['site.env_name'] = 'development';
// sneakers top page
$config['site.sneakers.static_content_url'] = 'https://b-www.minikura.com/contents/sneakers/';

//* market 
/*
* コンテンツ側からApacheでAlias設定中 
* 見た目コンテンツのURLを表示し、mypage側を参照する。絶対ドメインパスが必要になる
*/
//* Env Switch
switch (true) {
    //** Console
    case (! isset($_SERVER['SERVER_NAME'])):
        break;

    //** 
    //case $_SERVER['SERVER_NAME'] === 'goto-contents.minikura.com':
    case $_SERVER['HTTP_HOST'] === 'osada-contents.minikura.com':
        $config['site.mypage.url'] = 'https://osada-mypage.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'goto-contents.minikura.com':
        $config['site.mypage.url'] = 'https://goto-mypage.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'harasawa.minikura.com':
        $config['site.mypage.url'] = 'https://harasawa-mypage.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'maekawa-contents.minikura.com':
        $config['site.mypage.url'] = 'https://maekawa-mypage.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'rikiji-contents.minikura.com':
        $config['site.mypage.url'] = 'https://rikiji-mypage.minikura.com';
        break;
    default:
        $config['site.mypage.url'] = 'https://' . $_SERVER['HTTP_HOST'];
}

/**
 * エラー表示
 */
// CakePHP Debug Level
Configure::write('debug', 2);
// php display
ini_set('display_errors', '1');
error_reporting(E_ALL);
