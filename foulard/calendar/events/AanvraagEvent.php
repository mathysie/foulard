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
        $this->tappers = $this->setTappers($event->summary);
        $this->start = new GoogleDateTime($event->getStart());

        $aanvragen_lijst = $this->getAanvragenLijst($event->summary);
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

    protected function setTappers(string $summary): array
    {
        $tappers = explode(' - ', $summary, 2);
        if (count($tappers) > 1) {
            return explode(', ', $tappers[1]);
        } else {
            return [];
        }
    }

    protected function getAanvragenLijst(string $summary): string
    {
        return explode(' - ', $summary, 2)[0];
    }
}
