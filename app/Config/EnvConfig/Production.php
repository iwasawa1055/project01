<?php
// Manual Configure & Startup Configure

//*** Env
$config['app']['env'] = 'prod';

define('HTTP', 'http://');
define('HTTPS', 'https://');
define('HOST', $_SERVER['SERVER_NAME']);

//*** Sender
$config['app']['e']['mail']['sender'] = array(
	'HOST' => 'localhost',
	'PORT' => 25,
	'MAIL FROM' => 'info@terrada.jp',
	'USER' => '',
	'PASS' => '',
);

//*** Receiver
$config['app']['e']['mail']['receiver'] = array(
	'warning' => array(
		'To' => array(
			'minikura.developer@gmail.com',
		),
	),
	'critical' => array(
		'To' => array(
			'minikura.developer@gmail.com',
		),
		'Cc' => array(
			'minikura.developer@gmail.com',
		),
	),
);

//*** Debug
ini_set('error_reporting', E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '1');
Configure::write('debug', '2');
$config['app']['e']['mail'] = false;
$config['app']['e']['mail']['flag'] = false;

//*** Log
CakeLog::drop('bench');
CakeLog::drop('debug');
