<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

	// 2015/08 comment out by osada@terrada
	//Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	// 2015/08 comment out by osada@terrada
	//Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/', array('controller' => 'top', 'action' => 'index'));
	Router::connect('/index', array('controller' => 'top', 'action' => 'index'));

	Router::connect('/customer/:controller/', array('action' => 'index'));
	Router::connect('/customer/:controller/:action', array());

	Router::connect('/inbound/box/:action', array('controller' => 'InboundBox'));
	Router::connect('/inbound/shoe/:action', array('controller' => 'InboundShoe'));

	Router::connect('/outbound/box/', array('controller' => 'OutboundBox', 'action' => 'index'));
	Router::connect('/outbound/box/:action', array('controller' => 'OutboundBox'));
	Router::connect('/outbound/item/', array('controller' => 'OutboundItem', 'action' => 'index'));
	Router::connect('/outbound/item/:action', array('controller' => 'OutboundItem'));

	Router::connect('/:controller/detail/:id', array('action' => 'detail'));
	Router::connect('/:controller/detail/:id/:action', array());

	Router::connect('/:controller/:id/:action', array());

	// 2015/08 added by osada@terrada
	Router::connect('/:controller/:action', array());
	

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
