<?php
// Manual Configure & Startup Configure
$config['site.name'] = 'minikura.com';
// 本サイトのURL（パスワードリセットメールの本文で使用）
$config['site.url'] = 'https://' . $_SERVER['HTTP_HOST'];
// 未ログイン時TOPメニューのリンク先
$config['site.top_page'] = 'https://' . $_SERVER['HTTP_HOST'];
// 静的コンテンツ用ドメイン
$config['site.static_content_url'] = 'https://minikura.com';
// 環境文字列（development, staging, production）
$config['site.env_name'] = 'production';
// sneakers top page
$config['site.sneakers.static_content_url'] = 'https://minikura.com/contents/sneakers/';

//* trade 
/*
* コンテンツ側からApacheでAlias設定中 
* 見た目コンテンツのURLを表示し、mypage側を参照する。絶対ドメインパスが必要になる
*/
$config['site.mypage.url'] = 'https://mypage.minikura.com';
$config['site.trade.url'] = 'https://minikura.com/trade/';

/**
 * エラー表示
 */
// CakePHP Debug Level
Configure::write('debug', 0);
// php display
ini_set('display_errors', '0');
error_reporting(E_ALL ^ E_NOTICE);

/**
 * API設定
 */
$config['api.minikura.oem_key'] = 'jaLt9UbT2rib9GJOclnTgsgDMqwT8BoXhYo.bLsGtnmHzVXQdX0ESw--';
$url = 'https://apiv3.minikura.com';
$config['api.minikura.access_point.minikura_v3'] = $url . '/v3/warehouse/minikura';
$config['api.minikura.access_point.minikura_v4'] = $url . '/v4/minikura';
$config['api.minikura.access_point.minikura_v5'] = $url . '/v5/minikura';
$config['api.minikura.access_point.gmopayment_v4'] = $url . '/v4/gmo_payment';
$config['api.minikura.access_point.gmopayment_v5'] = $url . '/v5/gmo_payment';
$config['api.minikura.access_point.cpss_v5'] = $url . '/v5/cpss';

/**
 * nike_snkrs用
 */
$config['api.sneakers.oem_key'] = 'ABSJCmGg6Uwm4m9031AbHtaeELCC3q10je0ZvTdfVDYp_x8Hzb8sCmgAQdtnmJ.QIX7HfB.hNKo-';

//* strage server
$config['api.strage.host'] = '192.168.16.191';
$config['api.strage.file_dir'] = '/data/s/image.minikura.com/app/webroot/i/';
$config['api.strage.url'] = 'http://image.minikura.com/i/';
$config['api.strage.ssh.username'] = 'minikura.com';
$config['api.strage.ssh.rsa.id_rsa_public'] = '/home/minikura.com/.ssh/id_rsa.pub';
$config['api.strage.ssh.rsa.id_rsa'] = '/home/minikura.com/.ssh/id_rsa';


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

$config['app']['switch_redirect']['session_name'] = 'switch_pedirect_option';
$config['app']['switch_redirect']['param'] = 'option';

/**
 * 静的ページからの最大注文箱数
 */
$config['app']['first_order']['max_box'] = 20;

/**
 * 静的ページからの最大注文箱数
 */
$config['app']['first_order']['kit']['none_starter'] = array(
    'mono'          => array('code' => KIT_CD_MONO,             'name' => 'MONO レギュラーボックス',     'price' => 250),
    'mono_apparel'  => array('code' => KIT_CD_MONO_APPAREL,     'name' => 'MONO アパレルボックス',      'price' => 250),
    'mono_book'     => array('code' => KIT_CD_MONO_BOOK,        'name' => 'MONO ブックボックス',       'price' => 250),
    'hako'          => array('code' => KIT_CD_HAKO,             'name' => 'HAKO レギュラーボックス',     'price' => 200),
    'hako_apparel'  => array('code' => KIT_CD_HAKO_APPAREL,     'name' => 'HAKO アパレルボックス',      'price' => 200),
    'hako_book'     => array('code' => KIT_CD_HAKO_BOOK,        'name' => 'HAKO ブックボックス',       'price' => 200),
    'cleaning'      => array('code' => KIT_CD_CLEANING_PACK,    'name' => 'クリーニングパック',          'price' => 12000),
);

$config['app']['first_order']['starter_kit']['code'] = array(
    'starter_mono'          => KIT_CD_STARTER_MONO,
    'starter_mono_apparel'  => KIT_CD_STARTER_MONO_APPAREL,
    'starter_mono_book'     => KIT_CD_STARTER_MONO_BOOK,
);

$config['app']['first_order']['starter_kit']['price'] = '250';
$config['app']['first_order']['starter_kit']['name'] = 'minikura スターターパック';

// クリーニング費用設定
$config['app']['kit']['cleaning']['item_group_cd']['010'] = 650;
$config['app']['kit']['cleaning']['item_group_cd']['030'] = 3500;

$config['app']['kit']['cleaning']['work_type']['010'] = "030";
$config['app']['kit']['cleaning']['work_type']['030'] = "032";


//*** Log
// 不要なログはDropします。
// CakeLog::drop('error');
// CakeLog::drop('mail');
CakeLog::drop('bench');
CakeLog::drop('debug');

/**
 * エラーメール設定
 */
$config['app']['e']['mail'] = [
    'flag' => true,
    'receiver' => [
        'warning' => [
            'To' => [],
            'Cc' => [],
            'Bcc' => [],
        ],
        'error' => [
            'To' => [],
            'Cc' => [],
            'Bcc' => [],
        ],
        'critical' => [
            'To' => [],
            'Cc' => [],
            'Bcc' => [],
        ]
    ]
];
