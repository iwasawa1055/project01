<?php
// Manual Configure & Startup Configure

/**
 * 共通設定
 */
$config['site.name'] = 'Minikura.com';
// 本サイトのURL（パスワードリセットメールの本文で使用）
$config['site.url'] = 'http://' . $_SERVER['HTTP_HOST'];
// 未ログイン時TOPメニューのリンク先
$config['site.top_page'] = 'http://' . $_SERVER['HTTP_HOST'];
// 静的コンテンツ用ドメイン
$config['site.static_content_url'] = 'http://' . $_SERVER['HTTP_HOST'];
// 環境文字列（development, staging, production）
$config['site.env_name'] = '';
/**
 * API設定
 */
//$url = 'https://a-api.minikura.com';
$url = 'https://dev-api.minikura.com';
$config['api.minikura.oem_key'] = 'mB9JCKud0_o_yQgYYhulLTpuR9plqU5BjkXU9pgb_tiyn16xwfxpSA--';
$config['api.minikura.access_point.minikura_v3'] = $url . '/v3/warehouse/minikura';
$config['api.minikura.access_point.minikura_v4'] = $url . '/v4/minikura';
$config['api.minikura.access_point.minikura_v5'] = $url . '/v5/minikura';
$config['api.minikura.access_point.gmopayment_v4'] = $url . '/v4/gmo_payment';
$config['api.minikura.access_point.gmopayment_v5'] = $url . '/v5/gmo_payment';
$config['api.minikura.access_point.cpss_v5'] = $url . '/v5/cpss';
$config['api.minikura.access_point.amazon_pay_v4'] = $url . '/v4/amazon_pay';
$config['api.minikura.access_point.amazon_pay_v5'] = $url . '/v5/amazon_pay';
$config['api.minikura.access_point.facebook_v5'] = $url . '/v5/facebook';
$config['api.minikura.access_point.google_v5'] = $url . '/v5/google';

/**
 * nike_snkrs用
 *  @todo oem_keyが決まり次第  EnvConfig/Staging, EnvConfig/Productionにも同様に設定する
 *  @todo alliance_cdが決まり次第
 */
$config['api.sneakers.oem_key'] = '1gI.NKWGSgpMzJevM3PNJLvKrbzcVkIvE_WQMIJ_ij.AH.8z_Vd.J29tPSClUn1HUDfLhYrPnuE-';
$config['api.sneakers.alliance_cd'] = 'sneakers';
$config['api.sneakers.dir'] = 'sneakers';
$config['api.sneakers.file.key_list'] = 'my_sneakers_minikura_key.txt';
$config['api.sneakers.file.registered_list'] = 'registered_list.txt';
$config['api.sneakers.file.error_list'] = 'error_list_';
$config['api.sneakers.action_name.add'] = 'customer_add_sneakers';
$config['api.sneakers.action_name.confirm'] = 'customer_confirm_sneakers';
$config['api.sneakers.action_name.complete'] = 'customer_complete_sneakers';
$config['site.sneakers.MY_SNKRS_url'] = 'https://nike.jp/sportswear/my-snkrs/';

// タイムアウト（秒）
$config['api.timeout'] = 40;
$config['api.connect_timeout'] = 40;
$config['api.user_agent'] = 'minikura';

// APIリトライ設定
$config['api.retry_max_num'] = 5;
$config['api.retry_sleep_sec'] = 0.5;

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
$config['app']['first_order']['direct_inbound']['max_box'] = 20;

/**
 * Amazon Pay 環境変更
 */
$config['app']['amazon_pay']['Widgets_url'] = 'https://static-fe.payments-amazon.com/OffAmazonPayments/jp/sandbox/lpa/js/Widgets.js';
$config['app']['amazon_pay']['Amazon_MWS_EP'] = 'https://mws.amazonservices.jp/OffAmazonPayments_Sandbox/2013-01-01/';
$config['app']['amazon_pay']['profile_API_EP'] = 'https://api-sandbox.amazon.co.jp/user/profile';

/**
 * Amazon Pay 設定値
 */
$config['app']['amazon_pay']['merchant_id'] = 'A1MBRBB8GPQFL9'; // 出品者ID
$config['app']['amazon_pay']['access_key'] = 'AKIAIZZ2IUFQHH5JOZEQ';
$config['app']['amazon_pay']['secret_key'] = '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';
$config['app']['amazon_pay']['client_id'] = 'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e';
$config['app']['amazon_pay']['region'] = 'jp';
$config['app']['amazon_pay']['sandbox'] = 'true';

/**
 * Google client_id 設定値
 */
$config['app']['google']['client_id'] = '56091862582-mljt29dmcdgcj1fojhaqqpom9ud4mige.apps.googleusercontent.com';

/**
 * エラー表示(デフォルトは表示)
 */
// CakePHP Debug Level
Configure::write('debug', 2);
// php display
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Log
// 不要なログはDrop
// CakeLog::drop('error');
// CakeLog::drop('mail');
// CakeLog::drop('bench');
// CakeLog::drop('debug');


/**
 * エラーメール設定
 */
$config['app']['e']['mail'] = [
    'flag' => true,
    'receiver' => [
        'warning' => [
            'To' => [
                'exception_mail_warning_to@example.com',
            ],
            'Cc' => [
                'exception_mail_warning_cc@example.com',
            ],
            'Bcc' => [
                'exception_mail_warning_bcc@example.com',
            ]
        ],
        'error' => [
            'To' => [
                'exception_mail_warning_to@example.com',
            ],
            'Cc' => [
                'exception_mail_warning_cc@example.com',
            ],
            'Bcc' => [
                'exception_mail_warning_bcc@example.com',
            ]
        ],
        'critical' => [
            'To' => [
                'exception_mail_critical_to1@example.com',
                'exception_mail_critical_to2@example.com',
            ],
            'Cc' => [
                'exception_mail_critical_cc1@example.com',
                'exception_mail_critical_cc2@example.com',
            ],
            'Bcc' => [
                'exception_mail_critical_bcc1@example.com',
                'exception_mail_critical_bcc@example.com',
            ]
        ]
    ]
];
