<?php

declare(strict_types=1);

namespace overhemd\calendar\events;

use Google_Service_Calendar_Event;
use mako\validator\Validator;
use mako\validator\ValidatorFactory;
use overhemd\calendar\aanvragen\borrels\AegirBorrel;
use overhemd\calendar\aanvragen\borrels\RegulierBorrel;
use overhemd\calendar\aanvragen\borrels\TappersBedankBorrel;
use overhemd\calendar\aanvragen\PersoonlijkAanvraag;
use overhemd\calendar\Event;
use overhemd\datetime\GoogleDateTime;
use overhemd\datetime\OverhemdDateTime;

class AanvraagEvent extends Event
{
    /** @var array */
    public $tappers = [];

    /** @var int */
    public $tap_min;

    /** @var OverhemdDateTime */
    public $start;

    /** @var string */
    public $startdatum;

    /** @var string */
    public $starttijd;

    /** @var OverhemdDateTime */
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
        'tap_min'    => ['integer'],
        'startdatum' => ['required', 'date("Y-m-d")'],
        'starttijd'  => ['required', 'date("H:i")'],
        'einddatum'  => ['required', 'date("Y-m-d")'],
        'eindtijd'   => ['required', 'date("H:i")'],
        'start'      => ['required'],
        'eind'       => ['required'],
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

    public function update()
    {
        $this->event->setStart($this->start->getGoogleDateTime());
        $this->event->setEnd($this->eind->getGoogleDateTime());
        $this->event->setSummary($this->createSummary());
        $this->event->setDescription($this->createDescription());

        $this->calendarHelper->updateEvent($this->event);
    }

    public function getAanvragenLijst(): string
    {
        return explode(' - ', $this->event->summary, 2)[0];
    }

    public function getTappers(): string
    {
        return implode(', ', $this->tappers);
    }

    public function isValid(
        ValidatorFactory $validatorFactory,
        ?array &$errors = []
    ): bool {
        $validator = $validatorFactory->create($this->toArray(), $this->rules);

        $validator->addRulesIf('aanvragen', ['required'], function () {
            return 0 === count($this->aanvragen);
        });
        $validator->addRulesIf(
            'eind',
            [
                Validator::rule(
                    'after',
                    OverhemdDateTime::FORMAT_YMD_TIME,
                    $this->start->formatYMDTime()
                ),
            ],
            function () {
                return $this->eind < $this->start;
            }
        );

        return $validator->isValid($errors);
    }

    protected function createSummary(): string
    {
        $summary = '';
        $aanvragen_summary = [];

        foreach ($this->aanvragen as $key => $aanvraag) {
            $aanvragen_summary[$key] = trim($aanvraag->summary);
            if ($aanvraag->kwn) {
                $aanvragen_summary[$key] .= ' incl. ';
                if ($aanvraag->kwn_port > 0) {
                    $aanvragen_summary[$key] .= "{$aanvraag->kwn_port}x ";
                }
                $aanvragen_summary[$key] .= 'KWN';
            }

            if ($aanvraag->pers > 0) {
                $aanvragen_summary[$key] .= " ({$aanvraag->pers} pers.)";
            }
        }

        $summary .= implode(' + ', $aanvragen_summary);

        if (!empty($this->tappers)) {
            $summary .= ' - ' . implode(', ', $this->tappers);
        }

        return trim($summary);
    }

    protected function createDescription(): string
    {
        $description = '';
        $aanvragen_description = [];

        if ($this->tap_min !=
                $this->config->get('overhemd.aanvraag.default.tap-min')) {
            $description .= sprintf('%s: %d<br><br>',
                $this->config->get('overhemd.aanvraag.text.tap-min'),
                $this->tap_min);
        }

        foreach ($this->aanvragen as $key => $aanvraag) {
            if ($aanvraag->hasDescription()) {
                $aanvragen_description[$key] = sprintf(
                    "<i>%s '%s'</i>",
                    $this->config->get('overhemd.aanvraag.text.borrel'),
                    $aanvraag->summary
                );

                if ($aanvraag instanceof PersoonlijkAanvraag) {
                    $aanvragen_description[$key] .= '<br>' .
                        $this->config->get('overhemd.aanvraag.text.persoonlijk');
                }

                if (!empty($aanvraag->contactpersoon)) {
                    $aanvragen_description[$key] .= sprintf(
                        '<br><b>%s</b>: %s',
                        $this->config->get('overhemd.aanvraag.text.contact'),
                        $aanvraag->contactpersoon
                    );
                }

                if (!is_null($aanvraag->sap)) {
                    $aanvragen_description[$key] .= sprintf(
                        '<br><b>%s</b>: %d',
                        $this->config->get('overhemd.aanvraag.text.sap'),
                        $aanvraag->sap
                    );
                }

                if (!empty($aanvraag->description)) {
                    $aanvragen_description[$key] .= sprintf(
                        '<br><b>%s</b>:<br>%s',
                        $this->config->get('overhemd.aanvraag.text.bijzonder'),
                        $aanvraag->description
                    );
                }
            }
        }

        $description .= implode('<br><br>', $aanvragen_description);

        return trim($description);
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

        if (array_key_exists($match[1] ?? null, $this->borrels)) {
            $this->aanvragen[] = new $this->borrels[$match[1]](
                $aanvraag,
                $description,
                true
            );
        } else {
            $this->aanvragen[] = $this->calendarParser->parseAanvraag(
                $aanvraag,
                $description,
                true
            );
        }
    }

    protected function setTapMin(?string $description): void
    {
        $pattern = sprintf(
            '/^%s: (\d+)[\s\r\n]*/mi',
            $this->config->get('overhemd.aanvraag.text.tap-min')
        );

        if (preg_match($pattern, $description ?? '', $match)) {
            $this->tap_min = $match[1];
        } else {
            $this->tap_min = $this->config->get('overhemd.aanvraag.default.tap-min');
        }
    }

    protected function parseEventDescription(?string $description, string $aanvraag): string
    {
        if (is_null($description)) {
            return '';
        }

        $pattern = sprintf(
            '/<i>%s \'(.*)\'<\/i>(?:<br>)+/i',
            $this->config->get('overhemd.aanvraag.text.borrel')
        );
        preg_match_all($pattern, $description, $matches);

        foreach ($matches[1] as $key => $match) {
            if (preg_match("/{$match}/i", $aanvraag)) {
                return preg_split($pattern, $description)[$key + 1];
            }
        }

        return '';
    }

    protected function toArray(): array
    {
        return get_object_vars($this);
    }
}
