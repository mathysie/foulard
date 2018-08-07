<?php

namespace foulard\calendar\events;

use foulard\calendar\Event;
use Google_Service_Calendar_Event;

abstract class AanvraagEvent extends Event
{
    const AANVRAGER = self::AANVRAGER;

    public function __construct(Google_Service_Calendar_Event $event)
    {
        parent::__construct($event);

        $this->type = 'aanvraag';
    }
}
