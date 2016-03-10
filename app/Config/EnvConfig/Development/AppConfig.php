<?php
// Manual Configure & Startup Configure

Configure::write('debug', '2');
ini_set('display_errors', '1');
// ini_set('error_reporting', E_ALL & ~E_DEPRECATED);
error_reporting(E_ALL ^ E_NOTICE);


$config['app']['e']['mail']['flag'] = false;


//*** Env
$config['app']['env'] = 'stag';


// API setting
$config['api.oem_key'] = 'mB9JCKud0_o_yQgYYhulLTpuR9plqU5BjkXU9pgb_tiyn16xwfxpSA--';
$config['api.minikura.schema'] = 'https://';
$config['api.minikura.host'] = 'a-api.minikura.com';
$config['api.minikura.access_point.minikura_v3'] = $config['api.minikura.schema'].$config['api.minikura.host'].'/v3/warehouse/minikura';
$config['api.minikura.access_point.minikura_v4'] = $config['api.minikura.schema'].$config['api.minikura.host'].'/v4/minikura';
$config['api.minikura.access_point.minikura_v5'] = $config['api.minikura.schema'].$config['api.minikura.host'].'/v5/minikura';
$config['api.minikura.access_point.gmopayment_v4'] = $config['api.minikura.schema'].$config['api.minikura.host'].'/v4/gmo_payment';


// error
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
// ini_set('error_reporting', E_ALL & ~E_DEPRECATED);
// ini_set('display_errors', '1');
// Configure::write('debug', '2');
// // $config['app']['e']['mail'] = false;
// $config['app']['e']['mail']['flag'] = true;
