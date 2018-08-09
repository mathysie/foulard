<?php

declare(strict_types=1);

namespace foulard\calendar;

use foulard\calendar\aanvragen\Aanvraag;
use foulard\calendar\aanvragen\DLFAanvraag;
use foulard\calendar\aanvragen\FooBarAanvraag;
use foulard\calendar\aanvragen\ISSCAanvraag;
use foulard\calendar\aanvragen\LIACSAanvraag;
use foulard\calendar\aanvragen\MIAanvraag;
use foulard\calendar\aanvragen\OverigeAanvraag;
use foulard\calendar\aanvragen\PersoonlijkAanvraag;
use foulard\calendar\aanvragen\RINOAanvraag;
use foulard\calendar\aanvragen\SBBAanvraag;
use foulard\calendar\events\AanvraagEvent;
use foulard\calendar\events\OverigEvent;
use foulard\calendar\events\SchoonmaakEvent;
use foulard\calendar\events\VergaderingEvent;
use foulard\datetime\GoogleDateTime;
use Google_Service_Calendar_Event;

class CalendarParser
{
    protected $event_hints = [
        'FooBarvergadering',
        'O: ',
        'S: ',
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
    public function parseEvents(array $events, string $event_class = ''): array
    {
        $res = [];

        foreach ($events as $event) {
            $date = (string) (new GoogleDateTime($event->getStart()));
            $parsed_event = $this->parseEvent($event);
            if (empty($event_class) ||
                    is_a($parsed_event, $event_class)) {
                $res[$date][] = $parsed_event;
            }
        }

        return $res;
    }

    public function parseAanvraag(
        string $aanvraag,
        ?string $description
    ): Aanvraag {
        $pattern = '/(' . implode('|', $this->aanvraag_hints) . ')/i';
        preg_match($pattern, $aanvraag, $match);

        if (empty($match)) {
            $pattern = '/^(' . PersoonlijkAanvraag::AANVRAGER . ')/i';
            preg_match($pattern, $description ?? '', $match);
        }

        switch (strtolower($match[1] ?? '')) {
            case strtolower(DLFAanvraag::AANVRAGER):
                return new DLFAanvraag($aanvraag);

            case strtolower(FooBarAanvraag::AANVRAGER):
                return new FooBarAanvraag($aanvraag);

            case strtolower(ISSCAanvraag::AANVRAGER):
                return new ISSCAanvraag($aanvraag);

            case strtolower(LIACSAanvraag::AANVRAGER):
                return new LIACSAanvraag($aanvraag);

            case strtolower(MIAanvraag::AANVRAGER):
                return new MIAanvraag($aanvraag);

            case strtolower(PersoonlijkAanvraag::AANVRAGER):
                return new PersoonlijkAanvraag($aanvraag);

            case strtolower(RINOAanvraag::AANVRAGER):
                return new RINOAanvraag($aanvraag);

            case strtolower(SBBAanvraag::AANVRAGER):
                return new SBBAanvraag($aanvraag);

            default:
                return new OverigeAanvraag($aanvraag);
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
            case 'FooBarvergadering':
                return new VergaderingEvent($event);

            case 'O: ':
                return new OverigEvent($event);

            case 'S: ':
                return new SchoonmaakEvent($event);

            default:
                return new AanvraagEvent($event);
        }
    }
}
