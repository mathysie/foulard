<?php

declare(strict_types=1);

namespace foulard\calendar\aanvragen;

abstract class Aanvraag
{
    const AANVRAGER = self::AANVRAGER;

    /** @var bool */
    public $kwn = false;

    /** @var int|null */
    public $pers = null;

    /** @var string */
    public $summary;

    public function __construct(string $summary)
    {
        $this->kwn = $this->setKWN($summary);
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

    protected function setKWN(string $summary): bool
    {
        return false !== strpos($summary, 'incl. KWN');
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
        $summary = preg_split('/\s*incl\. KWN/', $summary)[0];
        $summary = preg_split('/\s*\(\d+ pers\./', $summary)[0];

        return $summary;
    }
}
