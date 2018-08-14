<?php

declare(strict_types=1);

namespace foulard\calendar\events;

use foulard\calendar\aanvragen\borrels\AegirBorrel;
use foulard\calendar\aanvragen\borrels\RegulierBorrel;
use foulard\calendar\aanvragen\borrels\TappersBedankBorrel;
use foulard\calendar\Event;
use foulard\datetime\FoulardDateTime;
use foulard\datetime\GoogleDateTime;
use Google_Service_Calendar_Event;
use mako\validator\Validator;
use mako\validator\ValidatorFactory;

class AanvraagEvent extends Event
{
    /** @var array */
    public $tappers = [];

    /** @var int */
    public $tap_min = 2;

    /** @var GoogleDateTime */
    public $start;

    /** @var string */
    public $startdatum;

    /** @var string */
    public $starttijd;

    /** @var GoogleDateTime */
    public $eind;

    /** @var string */
    public $einddatum;

    /** @var string */
    public $eindtijd;

    /** @var array */
    public $aanvragen = [];

    protected $borrels = [
        'Ægirborrel'          => AegirBorrel::class,
        'Regulier'            => RegulierBorrel::class,
        'Tappersbedankborrel' => TappersBedankBorrel::class,
    ];

    /** @var array */
    protected $rules = [
        'tappers.*'  => ['alpha_unicode'],
        'tap_min'    => ['integer'],
        'startdatum' => ['required', 'date("Y-m-d")'],
        'starttijd'  => ['required', 'date("H:i")'],
        'einddatum'  => ['required', 'date("Y-m-d")'],
        'eindtijd'   => ['required', 'date("H:i")'],
        'start'      => ['required'],
    ];

    public function __construct(
        Google_Service_Calendar_Event $event
    ) {
        parent::__construct($event);

        $this->type = 'aanvraag';
        $this->setTappers($event->summary);
        $this->setTapMin($event->description);
        $this->start = new GoogleDateTime($event->getStart());
        $this->startdatum = $this->start->formatYMD();
        $this->starttijd = $this->start->formatTime();
        $this->eind = new GoogleDateTime($event->getEnd());
        $this->einddatum = $this->eind->formatYMD();
        $this->eindtijd = $this->eind->formatTime();

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

    public function isValid(ValidatorFactory $validatorFactory, ?array &$errors = []): bool
    {
        $validator = $validatorFactory->create($this->toArray(), $this->rules);

        $validator->addRulesIf('aanvragen', ['required'], function () {
            return 0 === count($this->aanvragen);
        });
        $validator->addRules('eind', ['required', Validator::rule(
            'after',
            FoulardDateTime::FORMAT_YMD_TIME,
            $this->start->formatYMDTime()
        )]);

        return $validator->isValid($errors);
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

    protected function setTapMin(?string $description): void
    {
        $pattern = '/^Minimum aantal tappers: (\d+)[\s\r\n]*/mi';
        if (preg_match($pattern, $description ?? '', $match)) {
            $this->tap_min = $match[1];
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

    protected function toArray(): array
    {
        return get_object_vars($this);
    }
}
