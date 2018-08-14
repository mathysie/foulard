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

    /** @var string */
    public $description = '';

    /** @var string */
    public $contactpersoon = '';

    /** @var int|null */
    public $sap;

    public function __construct(string $summary, string $description, bool $parse)
    {
        $this->setKWN($summary);
        $this->setPers($summary);
        $this->setSummary($summary);
        $this->setDescription($description, $parse);
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

    public function setSAP(string $sap): void
    {
        $sap = (int) preg_replace('/\D/', '', $sap);
        $this->sap = !empty($sap) ? $sap : null;
    }

    protected function setKWN(string $summary): void
    {
        preg_match('/incl\. (?:(\d)x )?KWN/', $summary, $match);

        $this->kwn = !empty($match);
        if (isset($match[1])) {
            $this->kwn_port = (int) $match[1];
        }
    }

    protected function setPers(string $summary): void
    {
        preg_match('/(\d+) pers/', $summary, $match);
        if (isset($match[1]) && !empty($match[1])) {
            $this->pers = (int) $match[1];
        }
    }

    protected function setSummary(string $summary): void
    {
        $summary = explode(' - ', $summary, 2)[0];
        $summary = preg_split('/\s*incl\. (?:(\d)x )?KWN/', $summary)[0];
        $summary = preg_split('/\s*\(\d+ pers\./', $summary)[0];

        $this->summary = $summary;
    }

    protected function parseDescription(string $description): void
    {
        $description = preg_split('/^Persoonlijk[\s\r\n]*/mi', $description ?? '');
        $description = count($description) > 1
                                ? $description[1] : $description[0];

        preg_match('/Contactpersoon: (.*)[\s\r\n]*/mi', $description, $match);
        if (isset($match[1])) {
            $this->contactpersoon = $match[1];
        }

        preg_match('/SAP-nummer: (.*)[\s\r\n]*/mi', $description, $match);
        if (isset($match[1])) {
            $this->setSap($match[1]);
        }

        preg_match(
            '/Bijzonderheden:[\s\r\n]*(.*)[\s\r\n]*/mi',
            $description,
            $match
        );
        if (isset($match[1])) {
            $this->description = $match[1];
        }
    }

    protected function setDescription(string $description, bool $parse): void
    {
        if ($parse) {
            $this->parseDescription($description);
        } else {
            $this->description = $description;
        }
    }
}
