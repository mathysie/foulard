<?php

declare(strict_types=1);

namespace foulard\datetime;

use DateTime;
use Google_Service_Calendar_EventDateTime;

class FoulardDateTime extends DateTime
{
    const FORMAT_GOOGLE = DateTime::RFC3339;
    const FORMAT_YMD = 'Y-m-d';
    const FORMAT_WEEK = 'W';
    const FORMAT_WEERGAVE = 'd-m-Y';
    const FORMAT_TIME = 'H:i';
    const FORMAT_YMD_TIME = 'Y-m-d H:i';

    public function __toString(): string
    {
        return $this->format(self::FORMAT_WEERGAVE);
    }

    public function formatGoogle(): string
    {
        return $this->format(self::FORMAT_GOOGLE);
    }

    public function formatYMD(): string
    {
        return $this->format(self::FORMAT_YMD);
    }

    public function formatWeek(): string
    {
        return $this->format('W');
    }

    public function formatOnderwerp(): string
    {
        return strftime('%-e %B', $this->getTimestamp());
    }

    public function formatTapmail(): string
    {
        return strftime('%a %-e %b', $this->getTimestamp());
    }

    public function formatTime(): string
    {
        return $this->format(self::FORMAT_TIME);
    }

    public function formatYMDTime(): string
    {
        return $this->format(self::FORMAT_YMD_TIME);
    }

    public function getGoogleDateTime(): Google_Service_Calendar_EventDateTime
    {
        $datetime = new Google_Service_Calendar_EventDateTime();
        $datetime->setDateTime($this->formatGoogle());
        $datetime->setTimeZone($this->getTimezone()->getName());

        return $datetime;
    }
}
