<?php

declare(strict_types=1);

namespace foulard\calendar\events\aanvragen\borrels;

use foulard\calendar\events\aanvragen\FooBarAanvraag;
use Google_Service_Calendar_Event;

class RegulierBorrel extends FooBarAanvraag
{
    public function __construct(Google_Service_Calendar_Event $event)
    {
        parent::__construct($event);

        $this->type = 'regulier';
    }
}
