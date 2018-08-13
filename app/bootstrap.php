<?php

declare(strict_types=1);

// This file gets included at the end of the application boot sequence

use foulard\google\CalendarHelper;

Locale::setDefault('nl');

$container->registerSingleton([CalendarHelper::class, 'calendarHelper'], CalendarHelper::class);
