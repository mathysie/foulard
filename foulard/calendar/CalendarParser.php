<?php

namespace foulard\calendar;

use foulard\calendar\events\AanvraagEvent;
use foulard\calendar\events\AegirborrelEvent;
use foulard\calendar\events\OverigEvent;
use foulard\calendar\events\SchoonmaakEvent;
use foulard\calendar\events\TappersbedankborrelEvent;
use foulard\calendar\events\VergaderingEvent;
use foulard\datetime\GoogleDateTime;
use Google_Service_Calendar_Event;

class CalendarParser
{
    protected $event_hints = [
        'Ægirborrel',
        'FooBarvergadering',
        'S: ',
        'Tappersbedankborrel',
        'O: ',
    ];

    /**
     * Parses an array of Google_Service_Calendar_Event
     * to an array of Event.
     *
     * @param array $events An array of Google_Service_Calendar_Event
     *
     * @return array An array with event date as key and the Events of that day
     */
    public function parseEvents(array $events): array
    {
        $res = [];

        foreach ($events as $event) {
            $parsed_event = $this->parseEvent($event);
            $date = (string) (new GoogleDateTime($event->getStart()));
            $res[$date][] = $parsed_event;
        }

        return $res;
    }

    /**
     * Parse Google_Service_Calendar_Event to Event.
     *
     * @param Google_Service_Calendar_Event $event
     *
     * @return Event
     */
    protected function parseEvent(Google_Service_Calendar_Event $event): Event
    {
        $pattern = '/(' . implode('|', $this->event_hints) . ')/A';
        preg_match($pattern, $event->summary, $match);

        if (empty($match)) {
            return new AanvraagEvent($event);
        }

        switch ($match[1]) {
            case 'Ægirborrel':
                return new AegirborrelEvent($event);

            case 'FooBarvergadering':
                return new VergaderingEvent($event);

            case 'O: ':
                return new OverigEvent($event);

            case 'S: ':
                return new SchoonmaakEvent($event);

            case 'Tappersbedankborrel':
                return new TappersbedankborrelEvent($event);
        }
    }
}
