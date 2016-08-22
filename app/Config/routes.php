<?php

if (! empty($_SERVER['REQUEST_URI'])) {
    switch (true) {
        case preg_match('{^/market}i', $_SERVER['REQUEST_URI']):
            Router::connect('/input/*', ['controller' => 'Market', 'action' => 'input']);
            Router::connect('/confirm/*', ['controller' => 'Market', 'action' => 'confirm']);
            Router::connect('/complete/*', ['controller' => 'Market', 'action' => 'complete']);
            Router::connect('/:controller/:action/*', []);
            Router::connect('/:controller/', ['action' => 'index']);
            Router::connect('/:action/*', ['controller' => 'Market']);
            Router::connect('/', ['controller' => 'Market', 'action' => 'index']);
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
            
            // sale
            Router::connect('/sale/item/:action', ['controller' => 'SaleItem']);
            Router::connect('/sale/item/:action/:id', ['controller' => 'SaleItem'], ['id' => '[A-Z\-0-9]+']);
            
            Router::connect('/sale/:action', ['controller' => 'Sale']);

            // purchase
            Router::connect('/purchase/:id', ['controller' => 'Purchase', 'action' => 'index'], ['id' => '[A-Z\-0-9]+']);
            Router::connect('/purchase/register/:action', ['controller' => 'PurchaseRegister']);

            // default
            Router::connect('/:controller', ['action' => 'index']);
            Router::connect('/:controller/:action');
            break;
    }
}

# added
CakePlugin::routes();

