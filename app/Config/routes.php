<?php

    // top
    Router::connect('/', ['controller' => 'mypage', 'action' => 'index']);

    // customer
    Router::connect('/customer/:controller', ['action' => 'index']);
    Router::connect('/customer/:controller/:action');
    Router::connect('/customer/:controller/:action/:step');

    // inbound
    Router::connect('/inbound/box', ['controller' => 'InboundBox', 'action' => 'index']);
    Router::connect('/inbound/box/:action', ['controller' => 'InboundBox']);
    Router::connect('/inbound/shoe', ['controller' => 'InboundShoe', 'action' => 'index']);
    Router::connect('/inbound/shoe/:action', ['controller' => 'InboundShoe']);

    // outbound
    Router::connect('/outbound/box', ['controller' => 'OutboundBox', 'action' => 'index']);
    Router::connect('/outbound/box/:action', ['controller' => 'OutboundBox']);
    Router::connect('/outbound/item', ['controller' => 'OutboundItem', 'action' => 'index']);
    Router::connect('/outbound/item/:action', ['controller' => 'OutboundItem']);

    // ids
    Router::connect('/:controller/detail/:id', ['action' => 'detail']);
    Router::connect('/:controller/detail/:id/:action');
    Router::connect('/:controller/:id/:action');

    // default
    Router::connect('/:controller', ['action' => 'index']);
    Router::connect('/:controller/:action');
