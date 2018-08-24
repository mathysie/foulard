<?php

declare(strict_types=1);

namespace overhemd\calendar\aanvragen;

use mako\application\Application;
use mako\config\Config;
use mako\validator\ValidatorFactory;

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

    /**
     * @var Config
     */
    protected $config;

    protected $rules = [
        'summary'  => ['required'],
        'kwn_port' => ['integer'],
        'pers'     => ['integer'],
        'sap'      => ['integer'],
    ];

    public function __construct(string $summary, string $description, bool $parse)
    {
        $container = Application::instance()->getContainer();
        $this->config = $container->get(Config::class);

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

    public function isValid(ValidatorFactory $validatorFactory, ?array &$errors = []): bool
    {
        $validator = $validatorFactory->create($this->toArray(), $this->rules);

        return $validator->isValid($errors);
    }

    public function hasDescription(): bool
    {
        return !is_null($this->sap)
            || !empty($this->description)
            || !empty($this->contactpersoon)
            || $this instanceof PersoonlijkAanvraag;
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

        $pattern = sprintf(
            '/%s: (.*)[\r\n]*/mi',
            $this->config->get('overhemd.aanvraag.text.contact')
        );
        preg_match($pattern, $description, $match);
        if (isset($match[1])) {
            $this->contactpersoon = $match[1];
        }

        $pattern = sprintf(
            '/%s: (.*)[\s\r\n]*/mi',
            $this->config->get('overhemd.aanvraag.text.sap')
        );
        preg_match($pattern, $description, $match);
        if (isset($match[1])) {
            $this->setSap($match[1]);
        }

        $pattern = sprintf(
            '/%s:[\s\r\n]*(.*)[\s\r\n]*$/mis',
            $this->config->get('overhemd.aanvraag.text.bijzonder')
        );
        preg_match(
            $pattern,
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

    protected function toArray(): array
    {
        return get_object_vars($this);
    }
}
