<?php

return [
    // Calendar ID's are no secret, the credentials to reach and edit them are.
    'calendarID' => '1' === getenv('FOULARD_PROD')
        ? 'q24tnl03hvka3hn0c8kcl8t3c0@group.calendar.google.com'
        : '4obbg2gu9nqhmf4ugnbe8vi9l4@group.calendar.google.com',
];
