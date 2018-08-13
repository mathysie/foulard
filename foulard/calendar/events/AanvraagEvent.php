<?php

declare(strict_types=1);

namespace foulard\calendar\events;

use foulard\calendar\aanvragen\borrels\AegirBorrel;
use foulard\calendar\aanvragen\borrels\RegulierBorrel;
use foulard\calendar\aanvragen\borrels\TappersBedankBorrel;
use foulard\calendar\CalendarParser;
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
            $pattern = '/(' . implode('|', array_keys($this->borrels)) . ')/A';
            preg_match($pattern, $aanvraag, $match);
            switch ($match[1] ?? '') {
                case 'Ægirborrel':
                    $this->aanvragen[] = new AegirBorrel($aanvraag);
                    break;

                case 'Regulier':
                    $this->aanvragen[] = new RegulierBorrel($aanvraag);
                    break;

                case 'Tappersbedankborrel':
                    $this->aanvragen[] = new TappersBedankBorrel($aanvraag);
                    break;

                default:
                    $this->aanvragen[] = (new CalendarParser())->parseAanvraag(
                        $aanvraag,
                        $event->description
                    );
            }
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
}
