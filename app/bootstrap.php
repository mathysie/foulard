<?php

declare(strict_types=1);

// This file gets included at the end of the application boot sequence

use overhemd\calendar\CalendarParser;
use overhemd\google\CalendarHelper;

Locale::setDefault('nl');

$container->registerSingleton([CalendarParser::class, 'calendarParser'], CalendarParser::class);
$container->registerSingleton([CalendarHelper::class, 'calendarHelper'], CalendarHelper::class);
