<?php

    // top
    Router::connect('/', ['controller' => 'mypage', 'action' => 'index']);

    // customer
    Router::connect('/customer/:controller', ['action' => 'index']);
    Router::connect('/customer/:controller/:action');

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
    Router::connect('/:controller/detail/:id', ['action' => 'detail'], ['id' => '[0-9]+']);
    Router::connect('/:controller/detail/:id/:action', [], ['id' => '[0-9]+']);
    Router::connect('/:controller/:id/:action', [], ['id' => '[0-9]+']);

    // default
    Router::connect('/:controller', ['action' => 'index']);
    Router::connect('/:controller/:action');
