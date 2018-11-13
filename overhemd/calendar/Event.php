<?php

declare(strict_types=1);

namespace overhemd\calendar;

use Google_Service_Calendar_Event;
use mako\application\Application;
use mako\config\Config;
use overhemd\google\CalendarHelper;

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

    /**
     * @var CalendarHelper
     */
    protected $calendarHelper;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Google_Service_Calendar_Event $event)
    {
        $this->event = $event;

        $container = Application::instance()->getContainer();
        $this->calendarParser = $container->get(CalendarParser::class);
        $this->calendarHelper = $container->get(CalendarHelper::class);
        $this->config = $container->get(Config::class);
    }
}
