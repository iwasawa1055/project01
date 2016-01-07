<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// TODO: デバック関数
function print_rh($e)
{
	echo '<pre>';
	print_r($e);
	echo '</pre>';
}

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

// 2015/08 added by osada@terrada
	App::build(array(
		'Controller' => array(
			ROOT . DS . APP_DIR . DS . 'Controller' . DS,
			ROOT . DS . APP_DIR . DS . 'Controller' . DS . 'Customer' . DS,
		),
		'View' => array(
			ROOT . DS . APP_DIR . DS . 'View' . DS,
			ROOT . DS . APP_DIR . DS . 'View' . DS . 'Customer' . DS,
		),
		'Model' => array(
			ROOT . DS . APP_DIR . DS . 'Model' . DS,
		),
	));


/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

// 2015/08 added by osada@terrada
CakePlugin::loadAll();

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');

// 2015/08 comment out by osada@terrada
/*
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
*/

// 2015/08 added by osada@terrada
// error log
if (! is_dir(LOGS . DS . 'error')) {
	mkdir(LOGS . DS . 'error', 2770);
}
$error_file = 'error' . DS . date('Ymd');
define('ERROR_LOG', 'error');
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => $error_file,
));

// 2015/08 added by osada@terrada
// access log
if (! is_dir(LOGS . DS . 'access')) {
	mkdir(LOGS . DS . 'access', 0770);
}
$access_file = 'access' . DS . date('Ymd');
define('ACCESS_LOG', 'access');
CakeLog::config('access', array(
	'engine' => 'File',
	'types' => array(),
	'file' => $access_file,
));

// 2015/08 added by osada@terrada
// mail log
if (! is_dir(LOGS . DS . 'mail')) {
	mkdir(LOGS . DS . 'mail', 2770);
}
$mail_file = 'mail' . DS . date('Ymd');
define('MAIL_LOG', 'mail');
CakeLog::config('mail', array(
	'engine' => 'File',
	'types' => array('mail'),
	'file' => $mail_file,
));

// 2015/08 added by osada@terrada
// debug log
if (! is_dir(LOGS . DS . 'debug')) {
	mkdir(LOGS . DS . 'debug', 2770);
}
$debug_file = 'debug' . DS . date('Ymd');
define('DEBUG_LOG', 'debug');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => $debug_file,
));

// 2015/08 added by osada@terrada
// bench log
if (! is_dir(LOGS . DS . 'bench')) {
	mkdir(LOGS . DS . 'bench', 2770);
}
$bench_file = 'bench' . DS . date('Ymd');
define('BENCH_LOG', 'bench');
CakeLog::config('bench', array(
	'engine' => 'File',
	'types' => array('bench'),
	'file' => $bench_file,
));

// 2015/08 added by osada@terrada
// error handler load
App::uses('AppErrorHandler', 'Lib');
App::uses('AppExceptionHandler', 'Lib');
App::uses('AppErrorException', 'Lib');
App::uses('AppE', 'Lib');
App::uses('AppInternalPass', 'Lib');
App::uses('AppInternalInfo', 'Lib');
App::uses('AppInternalNotice', 'Lib');
App::uses('AppInternalWarning', 'Lib');
App::uses('AppInternalDefect', 'Lib');
App::uses('AppInternalError', 'Lib');
App::uses('AppInternalCritical', 'Lib');
App::uses('AppInternalFatal', 'Lib');
App::uses('AppMedialPass', 'Lib');
App::uses('AppMedialInfo', 'Lib');
App::uses('AppMedialNotice', 'Lib');
App::uses('AppMedialWarning', 'Lib');
App::uses('AppMedialDefect', 'Lib');
App::uses('AppMedialError', 'Lib');
App::uses('AppMedialCritical', 'Lib');
App::uses('AppMedialFatal', 'Lib');
App::uses('AppExternalPass', 'Lib');
App::uses('AppExternalInfo', 'Lib');
App::uses('AppExternalNotice', 'Lib');
App::uses('AppExternalWarning', 'Lib');
App::uses('AppExternalDefect', 'Lib');
App::uses('AppExternalError', 'Lib');
App::uses('AppExternalCritical', 'Lib');
App::uses('AppExternalFatal', 'Lib');
App::uses('AppTerminalPass', 'Lib');
App::uses('AppTerminalInfo', 'Lib');
App::uses('AppTerminalNotice', 'Lib');
App::uses('AppTerminalWarning', 'Lib');
App::uses('AppTerminalDefect', 'Lib');
App::uses('AppTerminalError', 'Lib');
App::uses('AppTerminalCritical', 'Lib');
App::uses('AppTerminalFatal', 'Lib');
App::uses('AppExceptionRenderer', 'Lib/Error');

spl_autoload_register(function ($_class_name)
{
	if (0 === strpos($_class_name, 'App')) {
		$class_name = preg_replace('/^App/', APP_DIR, $_class_name);
		$class_path = ROOT . DS . $class_name;
	} else {
		$class_path = APP . 'Vendor' . DS . $_class_name;
	}
	$class_path = str_replace('\\', DS, $class_path) . '.php';
	if (is_file($class_path)) {
		@include_once $class_path;
	} else {
		$paths = explode('/', $class_path);
		$last = end($paths);
		//spl_autoload_call($last);
	}
});

// 2015/08 added by osada@terrada
Configure::load('AppConfig');

