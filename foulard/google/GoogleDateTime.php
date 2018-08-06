<?php

namespace foulard\google;

use DateTime;
use DateTimeZone;
use Google_Service_Calendar_EventDateTime;

class GoogleDateTime extends DateTime
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

    public function __toString()
    {
        return $this->format('Y-m-d');
    }
}
