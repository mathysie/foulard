<?php

declare(strict_types=1);

// This file gets included at the end of the application boot sequence

use mako\config\Config;
use mako\syringe\Container;
use nuno\NunoClient;
use overhemd\calendar\CalendarParser;
use overhemd\google\CalendarHelper;

Locale::setDefault('nl');

$container->registerSingleton([CalendarParser::class, 'calendarParser'], CalendarParser::class);
$container->registerSingleton([CalendarHelper::class, 'calendarHelper'], CalendarHelper::class);
$container->registerSingleton([NunoClient::class, 'nuno'], function (Container $container) {
    return $container->call(function (Config $config) {
        return new NunoClient($config->get('nuno.key'), $config->get('nuno.url'));
    });
});
