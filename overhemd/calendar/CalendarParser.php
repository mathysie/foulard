<?php

declare(strict_types=1);

namespace overhemd\calendar;

use Google_Service_Calendar_Event;
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

class CalendarParser
{
    protected $event_hints = [
        'FooBarvergadering' => VergaderingEvent::class,
        'Overig: '          => OverigEvent::class,
        'S: '               => SchoonmaakEvent::class,
    ];

    protected $aanvraag_hints = [
        DLFAanvraag::AANVRAGER         => DLFAanvraag::class,
        FooBarAanvraag::AANVRAGER      => FooBarAanvraag::class,
        ISSCAanvraag::AANVRAGER        => ISSCAanvraag::class,
        LIACSAanvraag::AANVRAGER       => LIACSAanvraag::class,
        MIAanvraag::AANVRAGER          => MIAanvraag::class,
        PersoonlijkAanvraag::AANVRAGER => PersoonlijkAanvraag::class,
        RINOAanvraag::AANVRAGER        => RINOAanvraag::class,
        SBBAanvraag::AANVRAGER         => SBBAanvraag::class,
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
        bool $parse,
        bool $persoonlijk = false
    ): Aanvraag {
        if ($persoonlijk) {
            return new PersoonlijkAanvraag($aanvraag, $description, $parse);
        }

        $pattern = sprintf(
            '/(%s)/i',
            implode('|', array_keys($this->aanvraag_hints))
        );
        preg_match($pattern, $aanvraag, $match);

        if (empty($match)) {
            $pattern = '/(' . PersoonlijkAanvraag::AANVRAGER . ')/i';
            preg_match($pattern, $description ?? '', $match);
        }

        if (array_key_exists($match[1] ?? null, $this->aanvraag_hints)) {
            return new $this->aanvraag_hints[$match[1]](
                $aanvraag,
                $description,
                $parse
            );
        } else {
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
        $pattern = '/(' . implode('|', array_keys($this->event_hints)) . ')/A';
        preg_match($pattern, $event->summary, $match);

        if (array_key_exists($match[1] ?? null, $this->event_hints)) {
            return new $this->event_hints[$match[1]]($event);
        } else {
            return new AanvraagEvent($event);
        }
    }
}
