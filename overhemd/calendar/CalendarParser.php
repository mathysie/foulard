<?php

declare(strict_types=1);

namespace overhemd\calendar;

use overhemd\calendar\aanvragen\Aanvraag;
use overhemd\calendar\aanvragen\DLFAanvraag;
use overhemd\calendar\aanvragen\FooBarAanvraag;
use overhemd\calendar\aanvragen\ISSCAanvraag;
use overhemd\calendar\aanvragen\LIACSAanvraag;
use overhemd\calendar\aanvragen\MIAanvraag;
use overhemd\calendar\aanvragen\OverigeAanvraag;
use overhemd\calendar\aanvragen\PersoonlijkAanvraag;
use overhemd\calendar\aanvragen\RINOAanvraag;
use overhemd\calendar\aanvragen\SBBAanvraag;
use overhemd\calendar\events\AanvraagEvent;
use overhemd\calendar\events\OverigEvent;
use overhemd\calendar\events\SchoonmaakEvent;
use overhemd\calendar\events\VergaderingEvent;
use overhemd\datetime\GoogleDateTime;
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
        string $description,
        bool $parse
    ): Aanvraag {
        $pattern = '/(' . implode('|', $this->aanvraag_hints) . ')/i';
        preg_match($pattern, $aanvraag, $match);

        if (empty($match)) {
            $pattern = '/(' . PersoonlijkAanvraag::AANVRAGER . ')/i';
            preg_match($pattern, $description ?? '', $match);
        }

        switch (strtolower($match[1] ?? '')) {
            case strtolower(DLFAanvraag::AANVRAGER):
                return new DLFAanvraag($aanvraag, $description, $parse);

            case strtolower(FooBarAanvraag::AANVRAGER):
                return new FooBarAanvraag($aanvraag, $description, $parse);

            case strtolower(ISSCAanvraag::AANVRAGER):
                return new ISSCAanvraag($aanvraag, $description, $parse);

            case strtolower(LIACSAanvraag::AANVRAGER):
                return new LIACSAanvraag($aanvraag, $description, $parse);

            case strtolower(MIAanvraag::AANVRAGER):
                return new MIAanvraag($aanvraag, $description, $parse);

            case strtolower(PersoonlijkAanvraag::AANVRAGER):
                return new PersoonlijkAanvraag($aanvraag, $description, $parse);

            case strtolower(RINOAanvraag::AANVRAGER):
                return new RINOAanvraag($aanvraag, $description, $parse);

            case strtolower(SBBAanvraag::AANVRAGER):
                return new SBBAanvraag($aanvraag, $description, $parse);

            default:
                return new OverigeAanvraag($aanvraag, $description, $parse);
        }
    }

    /**
     * Parse Google_Service_Calendar_Event to Event.
     *
     * @param Google_Service_Calendar_Event $event
     *
     * @return Event
     */
    public function parseEvent(Google_Service_Calendar_Event $event): Event
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
