<?php
// Manual Configure & Startup Configure
$config['site.url'] = 'http://production .' . $_SERVER['HTTP_HOST'];
$config['site.top_page'] = 'https://minikura.com';
$config['site.env_name'] = 'production';

/**
 * エラー表示
 */
// CakePHP Debug Level
Configure::write('debug', 0);
// php display
ini_set('display_errors', '0');
error_reporting(0);


/**
 * API設定
 */
// $config['api.minikura.oem_key'] = '';
// $config['api.minikura.access_point.minikura_v3'] = '';
// $config['api.minikura.access_point.minikura_v4'] = '';
// $config['api.minikura.access_point.minikura_v5'] = '';
// $config['api.minikura.access_point.gmopayment_v4'] = '';

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
        'critical' => [
            'To' => [],
            'Cc' => [],
            'Bcc' => [],
        ]
    ]
];
