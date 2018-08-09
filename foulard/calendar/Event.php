<?php

declare(strict_types=1);

namespace foulard\calendar;

use Google_Service_Calendar_Event;
use mako\application\Application;

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

    /**
     * @var CalendarParser
     */
    protected $calendarParser;

    public function __construct(Google_Service_Calendar_Event $event)
    {
        $this->event = $event;

        $container = Application::instance()->getContainer();
        $this->calendarParser = $container->get(CalendarParser::class);
    }
}
