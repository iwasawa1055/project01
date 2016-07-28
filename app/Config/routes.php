<?php

if (! empty($_SERVER['REQUEST_URI'])) {
	switch (true) {
		case preg_match('{^/c2c_sale}i', $_SERVER['REQUEST_URI']):
			Router::connect('/input/*', ['controller' => 'C2cSale', 'action' => 'input']);
			Router::connect('/confirm/*', ['controller' => 'C2cSale', 'action' => 'confirm']);
			Router::connect('/complete/*', ['controller' => 'C2cSale', 'action' => 'complete']);
			Router::connect('/:controller/:action/*', []);
			Router::connect('/:controller/', ['action' => 'index']);
			Router::connect('/:action/*', ['controller' => 'C2cSale']);
			Router::connect('/', ['controller' => 'C2cSale', 'action' => 'index']);
			break;
		default:
			// customer
			Router::connect('/customer/:controller', ['action' => 'index', 'customer' => true]);
			Router::connect('/customer/:controller/:action', ['customer' => true]);
			Router::connect('/customer/:controller/:action/:step', ['customer' => true]);

			// paymentng
			Router::connect('/paymentng/:controller/:action', ['paymentng' => true]);
			Router::connect('/paymentng/:controller/:action/:step', ['paymentng' => true]);

			// corporate
			Router::connect('/corporate/:controller/:action', ['corporate' => true]);

			// inbound
			Router::connect('/inbound/box', ['controller' => 'InboundBox', 'action' => 'index']);
			Router::connect('/inbound/box/:action', ['controller' => 'InboundBox']);
			Router::connect('/inbound/shoe', ['controller' => 'InboundShoe', 'action' => 'index']);
			Router::connect('/inbound/shoe/:action', ['controller' => 'InboundShoe']);

			// ids
			Router::connect('/:controller/:id/:action', [], ['id' => '[A-Z\-0-9]+']);
			Router::connect('/:controller/detail/:id', ['action' => 'detail'], ['id' => '[A-Z\-0-9]+']);
			Router::connect('/:controller/detail/:id/:action', [], ['id' => '[A-Z\-0-9]+']);

			// top
			Router::connect('/', ['controller' => 'MyPage', 'action' => 'index']);

			// default
			Router::connect('/:controller', ['action' => 'index']);
			Router::connect('/:controller/:action');
			break;
	}
}

# added
CakePlugin::routes();

