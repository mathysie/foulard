<?php

declare(strict_types=1);

namespace foulard\calendar\aanvragen;

abstract class Aanvraag
{
    const AANVRAGER = self::AANVRAGER;

    /** @var bool */
    public $kwn = false;

    /** @var int|null */
    public $kwn_port = null;

    /** @var int|null */
    public $pers = null;

    /** @var string */
    public $summary;

    public function __construct(string $summary)
    {
        $this->setKWN($summary);
        $this->pers = $this->setPers($summary);
        $this->summary = $this->setSummary($summary);
    }

    public function getTitel(): string
    {
        $titel = $this->summary;
        if ($this->kwn) {
            $titel .= ' incl. KWN';
        }
        if (!is_null($this->pers)) {
            $titel .= " ({$this->pers} pers.)";
        }

        return $titel;
    }

    protected function setKWN(string $summary): void
    {
        preg_match('/incl\. (?:(\d)x )?KWN/', $summary, $match);

        $this->kwn = !empty($match);
        if (isset($match[1])) {
            $this->kwn_port = (int) $match[1];
        }
    }

    protected function setPers(string $summary): ?int
    {
        preg_match('/(\d+) pers/', $summary, $match);
        if (isset($match[1]) && !empty($match[1])) {
            return (int) $match[1];
        } else {
            return null;
        }
    }

    protected function setSummary(string $summary): string
    {
        $summary = explode(' - ', $summary, 2)[0];
        $summary = preg_split('/\s*incl\. (?:(\d)x )?KWN/', $summary)[0];
        $summary = preg_split('/\s*\(\d+ pers\./', $summary)[0];

        return $summary;
    }
}
