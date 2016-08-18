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

//* market 
/*
* コンテンツ側からApacheでAlias設定中 
* 見た目コンテンツのURLを表示し、mypage側を参照する。絶対ドメインパスが必要になる
*/
$config['site.mypage.url'] = 'https://mypage.minikura.com' 

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
