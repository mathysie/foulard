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
        'prefix'    => '/calendar',
    ],
    function ($routes) {
        $routes->get('/tapmail/{offset}?', 'Calendar::getTapmail', 'calendar.tapmail');
    }
);
