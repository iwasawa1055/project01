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

$config['app']['first_order']['starter_kit']['price'] = '750';
$config['app']['first_order']['starter_kit']['name'] = 'minikura スターターパック';

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
