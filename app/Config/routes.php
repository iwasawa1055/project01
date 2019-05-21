<?php

// customer
Router::connect('/customer/:controller', ['action' => 'index', 'customer' => true]);
Router::connect('/customer/:controller/:action', ['customer' => true]);
Router::connect('/customer/:controller/:action/:step', ['customer' => true]);

// gift
Router::connect('/gift/:controller', ['action' => 'index', 'gift' => true]);
Router::connect('/gift/:controller/:action', ['gift' => true]);
Router::connect('/gift/:controller/:action/:step', ['gift' => true]);

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

# added
CakePlugin::routes();
