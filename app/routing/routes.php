<?php

$routes->group(
    ['namespace' => 'app\controllers'],
    function ($routes) {
        $routes->get('/', 'Index::getIndex');
    }
);

$routes->group(
    [
        'namespace' => 'app\controllers',
        'prefix'    => '/tapschema',
    ],
    function ($routes) {
        $routes->get('/{offset}?', 'Tapschema::getTapmail', 'tapschema.tapmail');
    }
);
