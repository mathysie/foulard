<?php

declare(strict_types=1);

namespace overhemd\calendar\events;

use Google_Service_Calendar_Event;
use overhemd\calendar\Event;

class OverigEvent extends Event
{
    public function __construct(Google_Service_Calendar_Event $event)
    {
        parent::__construct($event);

        $this->type = 'overig';
    }
}
