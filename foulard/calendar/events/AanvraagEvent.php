<?php

declare(strict_types=1);

namespace foulard\calendar\events;

use foulard\calendar\Event;
use Google_Service_Calendar_Event;

abstract class AanvraagEvent extends Event
{
    const AANVRAGER = self::AANVRAGER;

    /** @var array */
    public $tappers = [];

    /** @var bool */
    public $kwn = false;

    /** @var int|null */
    public $pers = null;

    public function __construct(Google_Service_Calendar_Event $event)
    {
        parent::__construct($event);

        $this->type = 'aanvraag';
        $this->tappers = $this->setTappers($event->summary);
        $this->kwn = $this->setKWN($event->summary);
        $this->pers = $this->setPers($event->summary);
        $this->event->setSummary($this->setSummary($event->summary));
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

    protected function setKWN(string $summary): bool
    {
        return false !== strpos($summary, 'incl. KWN');
    }

    protected function setPers(string $summary): int
    {
        preg_match('/(\d+) pers/', $summary, $match);
        if (isset($match[1]) && !empty($match[1])) {
            return (int) $match[1];
        } else {
            return 0;
        }
    }

    protected function setSummary(string $summary): string
    {
        $summary = explode(' - ', $summary, 2)[0];
        $summary = preg_split('/\s*incl\. KWN/', $summary)[0];
        $summary = preg_split('/\s*\(\d+ pers\./', $summary)[0];

        return $summary;
    }
}
