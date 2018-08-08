<?php

declare(strict_types=1);

namespace foulard\calendar;

use Google_Service_Calendar_Event;

abstract class Event
{
    /**
     * @var Google_Service_Calendar_Event
     */
    public $event;

    /**
     * @var string
     */
    public $type;

    public function __construct(Google_Service_Calendar_Event $event)
    {
        $this->event = $event;
    }
}
