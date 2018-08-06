<?php

$routes->group(
    ['namespace' => 'app\controllers'],
    function ($routes) {
        $routes->get('/', 'Index::welcome');
    }
);

$routes->group(
    [
        'namespace' => 'app\controllers',
        'prefix' => '/calendar',
    ],
    function ($routes) {
        $routes->get('/', 'Calendar::getOverzicht', 'calendar.overzicht');
    }
);
