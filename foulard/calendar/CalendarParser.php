<?php

declare(strict_types=1);

namespace foulard\calendar;

use foulard\calendar\events\AanvraagEvent;
use foulard\calendar\events\aanvragen\borrels\AegirBorrel;
use foulard\calendar\events\aanvragen\borrels\RegulierBorrel;
use foulard\calendar\events\aanvragen\borrels\TappersBedankBorrel;
use foulard\calendar\events\aanvragen\DLFAanvraag;
use foulard\calendar\events\aanvragen\FooBarAanvraag;
use foulard\calendar\events\aanvragen\ISSCAanvraag;
use foulard\calendar\events\aanvragen\LIACSAanvraag;
use foulard\calendar\events\aanvragen\MIAanvraag;
use foulard\calendar\events\aanvragen\OverigeAanvraag;
use foulard\calendar\events\aanvragen\PersoonlijkAanvraag;
use foulard\calendar\events\aanvragen\RINOAanvraag;
use foulard\calendar\events\aanvragen\SBBAanvraag;
use foulard\calendar\events\OverigEvent;
use foulard\calendar\events\SchoonmaakEvent;
use foulard\calendar\events\VergaderingEvent;
use foulard\datetime\GoogleDateTime;
use Google_Service_Calendar_Event;

class CalendarParser
{
    protected $event_hints = [
        'Ægirborrel',
        'FooBarvergadering',
        'O: ',
        'Regulier',
        'S: ',
        'Tappersbedankborrel',
    ];

    protected $aanvraag_hints = [
        DLFAanvraag::AANVRAGER,
        FooBarAanvraag::AANVRAGER,
        ISSCAanvraag::AANVRAGER,
        LIACSAanvraag::AANVRAGER,
        MIAanvraag::AANVRAGER,
        RINOAanvraag::AANVRAGER,
        SBBAanvraag::AANVRAGER,
    ];

    /**
     * Parses an array of Google_Service_Calendar_Event
     * to an array of Event, grouped by date.
     *
     * @param array $events      An array of Google_Service_Calendar_Event
     * @param array $event_class Events of only class $event_class get parsed
     *
     * @return array An array with event date as key and the Events of that day
     */
    public function parseEvents(array $events, $event_class = ''): array
    {
        $res = [];

        foreach ($events as $event) {
            $events_to_parse = $this->prepareEvents($event);
            $date = (string) (new GoogleDateTime($event->getStart()));

            foreach ($events_to_parse as $event_to_parse) {
                $parsed_event = $this->parseEvent($event_to_parse);
                if ('' === $event_class ||
                        is_subclass_of($parsed_event, $event_class)) {
                    $res[$date][] = $parsed_event;
                }
            }
        }

        return $res;
    }

    protected function prepareEvents(Google_Service_Calendar_Event $event): array
    {
        $new_events = [];

        $aanvragen = explode(' + ', $event->summary);
        foreach ($aanvragen as $aanvraag) {
            $new_event = clone $event;
            $new_event->setSummary($aanvraag);
            $new_events[] = $new_event;
        }

        return $new_events;
    }

    protected function parseAanvraag(
        Google_Service_Calendar_Event $event
    ): AanvraagEvent {
        $pattern = '/(' . implode('|', $this->aanvraag_hints) . ')/i';
        preg_match($pattern, $event->summary, $match);

        if (empty($match)) {
            $pattern = '/^(' . PersoonlijkAanvraag::AANVRAGER . ')/i';
            preg_match($pattern, $event->description ?? '', $match);
        }

        switch (strtolower($match[1] ?? '')) {
            case strtolower(DLFAanvraag::AANVRAGER):
                return new DLFAanvraag($event);

            case strtolower(FooBarAanvraag::AANVRAGER):
                return new FooBarAanvraag($event);

            case strtolower(ISSCAanvraag::AANVRAGER):
                return new ISSCAanvraag($event);

            case strtolower(LIACSAanvraag::AANVRAGER):
                return new LIACSAanvraag($event);

            case strtolower(MIAanvraag::AANVRAGER):
                return new MIAanvraag($event);

            case strtolower(PersoonlijkAanvraag::AANVRAGER):
                return new PersoonlijkAanvraag($event);

            case strtolower(RINOAanvraag::AANVRAGER):
                return new RINOAanvraag($event);

            case strtolower(SBBAanvraag::AANVRAGER):
                return new SBBAanvraag($event);

            default:
                return new OverigeAanvraag($event);
        }
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

        switch ($match[1] ?? '') {
            case 'Ægirborrel':
                return new AegirBorrel($event);

            case 'FooBarvergadering':
                return new VergaderingEvent($event);

            case 'O: ':
                return new OverigEvent($event);

            case 'Regulier':
                return new RegulierBorrel($event);

            case 'S: ':
                return new SchoonmaakEvent($event);

            case 'Tappersbedankborrel':
                return new TappersBedankBorrel($event);

            default:
                return $this->parseAanvraag($event);
        }
    }
}
