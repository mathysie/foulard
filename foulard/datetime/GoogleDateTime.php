<?php

namespace foulard\datetime;

use DateTimeZone;
use Google_Service_Calendar_EventDateTime;

class GoogleDateTime extends FoulardDateTime
{
    public function __construct(Google_Service_Calendar_EventDateTime $datetime)
    {
        parent::__construct();

        if (!empty($datetime->date)) {
            $this->modify($datetime->date);
        } elseif (!empty($datetime->dateTime)) {
            $this->modify($datetime->dateTime);
            $timeZone = $datetime->timeZone ?? 'Europe/Amsterdam';
            $this->setTimezone(new DateTimeZone($timeZone));
        }
    }
}
