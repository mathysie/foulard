<?php

declare(strict_types=1);

namespace foulard\calendar\events;

use foulard\calendar\aanvragen\borrels\AegirBorrel;
use foulard\calendar\aanvragen\borrels\RegulierBorrel;
use foulard\calendar\aanvragen\borrels\TappersBedankBorrel;
use foulard\calendar\Event;
use foulard\datetime\GoogleDateTime;
use Google_Service_Calendar_Event;

class AanvraagEvent extends Event
{
    /** @var array */
    public $tappers = [];

    /** @var GoogleDateTime */
    public $start;

    /** @var GoogleDateTime */
    public $eind;

    /** @var array */
    public $aanvragen = [];

    protected $borrels = [
        'Ægirborrel'          => AegirBorrel::class,
        'Regulier'            => RegulierBorrel::class,
        'Tappersbedankborrel' => TappersBedankBorrel::class,
    ];

    public function __construct(
        Google_Service_Calendar_Event $event
    ) {
        parent::__construct($event);

        $this->type = 'aanvraag';
        $this->setTappers($event->summary);
        $this->start = new GoogleDateTime($event->getStart());
        $this->eind = new GoogleDateTime($event->getEnd());

        $aanvragen_lijst = $this->getAanvragenLijst();
        foreach (explode(' + ', $aanvragen_lijst) as $aanvraag) {
            $this->setAanvraag($aanvraag);
        }
    }

    public function getAanvragenLijst(): string
    {
        return explode(' - ', $this->event->summary, 2)[0];
    }

    public function getTappers(): string
    {
        return implode(', ', $this->tappers);
    }

    protected function setTappers(string $summary): void
    {
        $tappers = explode(' - ', $summary, 2);
        $this->tappers = count($tappers) > 1 ? explode(', ', $tappers[1]) : [];
    }

    protected function setAanvraag(string $aanvraag): void
    {
        $pattern = '/(' . implode('|', array_keys($this->borrels)) . ')/A';
        preg_match($pattern, $aanvraag, $match);
        $description = $this->parseEventDescription(
            $this->event->description,
            $aanvraag
        );

        switch ($match[1] ?? '') {
            case 'Ægirborrel':
                $this->aanvragen[] = new AegirBorrel(
                    $aanvraag,
                    $description,
                    true
                );
                break;

            case 'Regulier':
                $this->aanvragen[] = new RegulierBorrel(
                    $aanvraag,
                    $description,
                    true
                );
                break;

            case 'Tappersbedankborrel':
                $this->aanvragen[] = new TappersBedankBorrel(
                    $aanvraag,
                    $description,
                    true
                );
                break;

            default:
                $this->aanvragen[] = $this->calendarParser->parseAanvraag(
                    $aanvraag,
                    $description,
                    true
                );
        }
    }

    protected function parseEventDescription(?string $description, string $aanvraag): string
    {
        $pattern = '/[\s\r\n]*Borrel \'?(.*)\'?:[\s\r\n]+/mi';
        preg_match_all($pattern, $description ?? '', $matches);

        foreach ($matches[1] as $key => $match) {
            if (preg_match("/{$match}/i", $aanvraag)) {
                return preg_split($pattern, $description)[$key + 1];
            }
        }
    }
}
