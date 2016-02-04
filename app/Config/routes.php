<?php

    // top
    Router::connect('/', ['controller' => 'mypage', 'action' => 'index']);

    // customer
    Router::connect('/customer/:controller', ['action' => 'index', 'customer' => true]);
    Router::connect('/customer/:controller/:action', ['customer' => true]);
    Router::connect('/customer/:controller/:action/:step', ['customer' => true]);

    // inbound
    Router::connect('/inbound/box', ['controller' => 'InboundBox', 'action' => 'index']);
    Router::connect('/inbound/box/:action', ['controller' => 'InboundBox']);
    Router::connect('/inbound/shoe', ['controller' => 'InboundShoe', 'action' => 'index']);
    Router::connect('/inbound/shoe/:action', ['controller' => 'InboundShoe']);

    // ids
    Router::connect('/:controller/detail/:id', ['action' => 'detail']);
    Router::connect('/:controller/detail/:id/:action');
    Router::connect('/:controller/:id/:action');

    // default
    Router::connect('/:controller', ['action' => 'index']);
    Router::connect('/:controller/:action');
