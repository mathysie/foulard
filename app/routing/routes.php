<?php

declare(strict_types=1);

$routes->group(
    ['namespace' => 'app\controllers'],
    function ($routes) {
        $routes->get('/', 'Index::getIndex');
        $routes->get('/logout', 'Login::logout', 'logout');
    }
);

$routes->group(
    [
        'namespace'  => 'app\controllers',
        'prefix'     => '/tapschema',
        'middleware' => 'authorization',
    ],
    function ($routes) {
        $routes->get('/{offset}?', 'Tapschema::getTapmail', 'tapschema.tapmail');
    }
);

$routes->group(
    [
        'namespace'  => 'app\controllers',
        'prefix'     => '/calendar',
        'middleware' => 'authorization',
    ],
    function ($routes) {
        $routes->get('/', 'Calendar::getOverzicht', 'calendar.overzicht');
        $routes->get('/bewerk/{id}', 'Calendar::bewerkAanvraag', 'calendar.bewerk.aanvraag');
        $routes->post('/bewerk/{id}', 'Calendar::updateAanvraag', 'calendar.update.aanvraag');
    }
);

$routes->group(
    [
        'namespace' => 'app\controllers',
        'prefix'    => '/login',
    ],
    function ($routes) {
        $routes->get('/', 'Login::askLogin', 'login.ask');
        $routes->post('/', 'Login::processLogin', 'login.process');
    }
);
