<?php
// Manual Configure & Startup Configure
$config['site.static_content_url'] = 'https://minikura.com';
$config['site.env_name'] = 'development';

/**
 * エラー表示
 */
// CakePHP Debug Level
Configure::write('debug', 2);
// php display
ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);
