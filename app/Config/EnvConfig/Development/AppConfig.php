<?php
// Manual Configure & Startup Configure
$config['site.static_content_url'] = 'https://minikura.com';
$config['site.env_name'] = 'development';
// sneakers top page
$config['site.sneakers.static_content_url'] = 'https://b-www.minikura.com/contents/sneakers/';

//* trade 
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
    case $_SERVER['SERVER_NAME'] === 'osada-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://osada-mypage.minikura.com';
        $config['site.trade.url'] = 'https://osada-contents.minikura.com/trade/';
		$config['site.static_content_url'] = 'https://osada-contents.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'goto-contents.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'goto-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://goto-mypage.minikura.com';
        $config['site.trade.url'] = 'https://goto-contents.minikura.com/trade/';
		$config['site.static_content_url'] = 'https://goto-contents.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'harasawa.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'harasawa-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://harasawa-mypage.minikura.com';
        $config['site.trade.url'] = 'https://harasawa.minikura.com/trade/';
		$config['site.static_content_url'] = 'https://harasawa-contents.minikura.com';
        break;

    case $_SERVER['HTTP_HOST'] === 'maekawa-contents.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'maekawa-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://maekawa-mypage.minikura.com';
        $config['site.trade.url'] = 'https://maekawa-contents.minikura.com/trade/';
		$config['site.static_content_url'] = 'https://maekawa-contents.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'murai-contents.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'murai-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://murai-mypage.minikura.com';
        $config['site.trade.url'] = 'https://murai-contents.minikura.com/trade/';
		$config['site.static_content_url'] = 'https://murai-contents.minikura.com';
        break;
    default:
        $config['site.mypage.url'] = 'https://' . $_SERVER['HTTP_HOST'];
        $config['site.trade.url'] = 'https://b-www.minikura.com/trade/';
}

//* strage server
$config['api.strage.host'] = '192.168.16.124';
$config['api.strage.file_dir'] = '/data/s/dev-image.minikura.com/app/webroot/i/';
$config['api.strage.url'] = 'http://dev-image.minikura.com:10080/i/';
$config['api.strage.ssh.username'] = 'minikura.com';
$config['api.strage.ssh.rsa.id_rsa_public'] = '/home/minikura.com/.ssh/id_rsa.pub';
$config['api.strage.ssh.rsa.id_rsa'] = '/home/minikura.com/.ssh/id_rsa';

// タイムアウト（秒）
$config['api.timeout'] = 30;
$config['api.connect_timeout'] = 30;
$config['api.user_agent'] = 'minikura';
/**
 * 恒久ログインlogout パラメータ
 */
$config['app']['login_cookie']['cookie_period'] = 60 * 60 * 24 * 180;
$config['app']['login_cookie']['param'] = 'logout';


/**
 * 会員登録用パラメタ―
 */
$config['app']['register']['birthyear']['birthyear_start'] = 1920;

/**
 * 静的ページからの遷移先変更
 */
$config['app']['lp_option']['param'] = 'option';
$config['app']['lp_code']['param'] = 'code';
$config['app']['sneaker_option']['param'] = 'key';

$config['app']['switch_redirect']['session_name'] = 'switch_pedirect_option';
$config['app']['switch_redirect']['param'] = 'option';

/**
 * 静的ページからの最大注文箱数
 */
$config['app']['first_order']['max_box'] = 20;
/**
 * エラー表示
 */
// CakePHP Debug Level
Configure::write('debug', 2);
// php display
ini_set('display_errors', '1');
error_reporting(E_ALL);
