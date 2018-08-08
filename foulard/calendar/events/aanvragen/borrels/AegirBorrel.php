<?php

declare(strict_types=1);

namespace foulard\calendar\events\aanvragen\borrels;

use foulard\calendar\events\aanvragen\DLFAanvraag;
use Google_Service_Calendar_Event;

class AegirBorrel extends DLFAanvraag
{
    public function __construct(Google_Service_Calendar_Event $event)
    {
        parent::__construct($event);

        $this->type = 'aegir';
    }
}
