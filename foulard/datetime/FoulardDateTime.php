<?php

namespace foulard\datetime;

use DateTime;

class FoulardDateTime extends DateTime
{
    const FORMAT_GOOGLE = DateTime::RFC3339;
    const FORMAT_YMD = 'Y-m-d';
    const FORMAT_WEEK = 'W';
    const FORMAT_WEERGAVE = 'd-m-Y';

    public function __toString()
    {
        return $this->format(self::FORMAT_WEERGAVE);
    }

    public function formatGoogle()
    {
        return $this->format(self::FORMAT_GOOGLE);
    }

    public function formatYMD()
    {
        return $this->format(self::FORMAT_YMD);
    }

    public function formatWeek()
    {
        return $this->format('W');
    }

    public function formatOnderwerp()
    {
        return strftime('%-e %B', $this->getTimestamp());
    }

    public function formatTapmail()
    {
        return strftime('%a %-e %b', $this->getTimestamp());
    }
}
