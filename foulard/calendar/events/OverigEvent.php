<?php

declare(strict_types=1);

namespace foulard\calendar\events;

use foulard\calendar\Event;
use Google_Service_Calendar_Event;

class OverigEvent extends Event
{
    public function __construct(Google_Service_Calendar_Event $event)
    {
        parent::__construct($event);

        $this->type = 'overig';
    }
}
