<?php
/**
 * 共通設定
 */
$config['site.name'] = 'Minikura.com';
// 本サイトのURL（パスワードリセットメールの本文で使用）
$config['site.url'] = 'https://' . $_SERVER['HTTP_HOST'];
// 未ログイン時TOPメニューのリンク先
$config['site.top_page'] = 'https://' . $_SERVER['HTTP_HOST'];
// 静的コンテンツ用ドメイン
$config['site.static_content_url'] = 'https://b-www.minikura.com';
// 環境文字列（development, staging, production）
$config['site.env_name'] = 'staging';
// sneakers top page
$config['site.sneakers.static_content_url'] = 'https://b-www.minikura.com/contents/sneakers/';

//* trade 
/*
* コンテンツ側からApacheでAlias設定中 
* 見た目コンテンツのURLを表示し、mypage側を参照する。絶対ドメインパスが必要になる
*/
$config['site.mypage.url'] = 'https://b-mypage.minikura.com';
$config['site.trade.url'] = 'https://b-www.minikura.com/trade/';

/**
 * エラー表示
 */
// CakePHP Debug Level
Configure::write('debug', 2);
// php display
ini_set('display_errors', '1');
error_reporting(E_ALL);

/**
 * API設定
 */
//* 通常は b-api 参照
$config['api.minikura.oem_key'] = '_QfNQL67x7RiIUbzn_1hkAEGKmlLO04we5rizUemfoWVLOjcEpIDDQ--';
$config['api.sneakers.oem_key'] = 'mIAy.vWMJewpodApFPeOp47LvITDqYFYJfnym4F.MLY8Q3qgxpBrjL749K1EuljzMVE9GMRAeiM-'; 
$url = 'https://b-api.minikura.com';

//* 開発結合テスト用 a-api 参照
//$config['api.minikura.oem_key'] = 'mB9JCKud0_o_yQgYYhulLTpuR9plqU5BjkXU9pgb_tiyn16xwfxpSA--';
//$config['api.sneakers.oem_key'] = '1gI.NKWGSgpMzJevM3PNJLvKrbzcVkIvE_WQMIJ_ij.AH.8z_Vd.J29tPSClUn1HUDfLhYrPnuE-';
//$url = 'https://a-api.minikura.com';

$config['api.minikura.access_point.minikura_v3'] = $url . '/v3/warehouse/minikura';
$config['api.minikura.access_point.minikura_v4'] = $url . '/v4/minikura';
$config['api.minikura.access_point.minikura_v5'] = $url . '/v5/minikura';
$config['api.minikura.access_point.gmopayment_v4'] = $url . '/v4/gmo_payment';
$config['api.minikura.access_point.gmopayment_v5'] = $url . '/v5/gmo_payment';
$config['api.minikura.access_point.cpss_v5'] = $url . '/v5/cpss';

//* strage server
$config['api.strage.host'] = '192.168.16.124';
$config['api.strage.file_dir'] = '/data/s/stag-image.minikura.com/app/webroot/i/';
$config['api.strage.url'] = 'http://stag-image.minikura.com:10080/i/';
$config['api.strage.ssh.username'] = 'minikura.com';
$config['api.strage.ssh.rsa.id_rsa_public'] = '/home/minikura.com/.ssh/id_rsa.pub';
$config['api.strage.ssh.rsa.id_rsa'] = '/home/minikura.com/.ssh/id_rsa';


/**
 * 恒久ログインlogout パラメータ
 */
$config['app']['login_cookie']['cookie_period'] = 60 * 60 * 24;
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


// クリーニング費用設定
$config['app']['kit']['cleaning']['item_group_cd']['010'] = 700;
$config['app']['kit']['cleaning']['item_group_cd']['030'] = 3800;

$config['app']['kit']['cleaning']['work_type']['010'] = "030";
$config['app']['kit']['cleaning']['work_type']['030'] = "032";

//*** Log
// 不要なログはDropします。
// CakeLog::drop('error');
// CakeLog::drop('mail');
//CakeLog::drop('bench');
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
