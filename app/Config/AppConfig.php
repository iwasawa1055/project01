<?php
// Manual Configure & Startup Configure

/**
 * 共通設定
 */
$config['site.name'] = 'Minikura.com';
$config['site.url'] = 'http://' . $_SERVER['HTTP_HOST'];
$config['site.top_page'] = 'http://' . $_SERVER['HTTP_HOST'];
$config['site.env_name'] = 'production';

/**
 * API設定
 */
$config['api.minikura.oem_key'] = 'mB9JCKud0_o_yQgYYhulLTpuR9plqU5BjkXU9pgb_tiyn16xwfxpSA--';
$url = 'https://a-api.minikura.com';
$config['api.minikura.access_point.minikura_v3'] = $url . '/v3/warehouse/minikura';
$config['api.minikura.access_point.minikura_v4'] = $url . '/v4/minikura';
$config['api.minikura.access_point.minikura_v5'] = $url . '/v5/minikura';
$config['api.minikura.access_point.gmopayment_v4'] = $url . '/v4/gmo_payment';

// タイムアウト（秒）
$config['api.timeout'] = 30;
$config['api.connect_timeout'] = 30;
$config['api.user_agent'] = 'minikura';


/**
 * エラー表示(デフォルトは表示)
 */
// CakePHP Debug Level
Configure::write('debug', 2);
// php display
ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);

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
