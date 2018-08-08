<?php

declare(strict_types=1);

return [
    // Calendar ID's are no secret, the credentials to reach and edit them are.
    'calendarID' => '1' === getenv('FOULARD_PROD')
        ? 'q24tnl03hvka3hn0c8kcl8t3c0@group.calendar.google.com'
        : 'p053d04c6d0hrdlrj86b86867k@group.calendar.google.com',
];
