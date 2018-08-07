<?php

namespace app\controllers;

use DateInterval;
use foulard\calendar\events\AanvraagEvent;
use foulard\calendar\events\aanvragen\borrels\AegirBorrel;
use foulard\calendar\events\aanvragen\borrels\TappersBedankBorrel;
use foulard\calendar\events\SchoonmaakEvent;
use foulard\datetime\FoulardDateTime;

class Tapschema extends BaseController
{
    public function getTapmail(int $offset = 0): string
    {
        $start = $this->getStart($offset);
        $end = $this->getEnd($start);
        $params = [
            'timeMin' => $start->formatGoogle(),
            'timeMax' => $end->formatGoogle(),
        ];

        $events = $this->calendarHelper->getEvents($start, $end, $params);
        $events = $this->calendarParser->parseEvents($events);
        $tapmail = $this->maakTapMail($events, $start);
        $rows = substr_count($tapmail, "\n") + 1;

        $view = $this->view->create('tapschema.overzicht');

        $view->assign('start', $start);
        $view->assign('end', $end);
        $view->assign('eerder', $offset - 1);
        $view->assign('later', $offset + 1);
        $view->assign('tapmail', $tapmail);
        $view->assign('rows', $rows);

        return $view->render();
    }

    protected function getStart(int $offset): FoulardDateTime
    {
        $start = new FoulardDateTime('next Monday');

        if ($offset > 0) {
            $start->add(new DateInterval("P{$offset}W"));
        } elseif ($offset < 0) {
            $offset = abs($offset);
            $start->sub(new DateInterval("P{$offset}W"));
        }

        return $start;
    }

    protected function getEnd(FoulardDateTime $start): FoulardDateTime
    {
        return new FoulardDateTime(sprintf(
            '%d Sundays after %s',
            $this->config->get('foulard.tapmail.weken'),
            $start->formatYMD()
        ));
    }

    protected function insertSchoonmaak(string &$schoonmakers): string
    {
        $schoonmaak = sprintf("Schoonmaak: %s\n", $schoonmakers);
        $schoonmakers = '';

        return $schoonmaak;
    }

    protected function insertScheiding(): string
    {
        return sprintf("%s\n\n", $this->config->get('foulard.tapmail.scheiding'));
    }

    protected function parseAanvragen(
        FoulardDateTime $datum,
        array $eventlijst,
        string &$schoonmakers
    ): string {
        $tekst = '';
        $aanvragenlijst = [];
        $overige_borrels = [];
        $tappers = '';

        foreach ($eventlijst as $event) {
            switch (true) {
                case $event instanceof AegirBorrel:
                case $event instanceof TappersBedankBorrel:
                    $overige_borrels[] = $event->event->summary;
                    break;

                case $event instanceof RegulierBorrel:
                    $summary = explode(' - ', $event->event->summary, 2);
                    array_unshift($aanvragenlijst, $summary[0]);
                    if (isset($summary[1])) {
                        $tappers = $summary[1];
                    }
                    break;

                case $event instanceof AanvraagEvent:
                    $summary = explode(' - ', $event->event->summary, 2);
                    $aanvragenlijst[] = $summary[0];
                    if (isset($summary[1])) {
                        $tappers = $summary[1];
                    }
                    break;

                case $event instanceof SchoonmaakEvent:
                    $schoonmakers = explode('S: ', $event->event->summary, 2)[1];
                    break;

                default:
                    break;
            }
        }

        if (!empty($aanvragenlijst)) {
            $tekst = sprintf(
                "%s: %s - %s\n\n",
                $datum->formatTapmail(),
                implode(' + ', $aanvragenlijst),
                !empty($tappers) ? $tappers : 'wie?'
            );
        }

        return $tekst;
    }

    protected function maakTapOverzicht(array $events, FoulardDateTime $start): string
    {
        $overzicht = '';
        $weeknummer = $start->formatWeek();
        $schoonmakers = '';

        $overzicht .= $this->insertScheiding();
        foreach ($events as $datum => $eventlijst) {
            $datum = new FoulardDateTime($datum);
            if ($datum->formatWeek() !== $weeknummer) {
                $overzicht .= $this->insertSchoonmaak($schoonmakers);
                $overzicht .= $this->insertScheiding();
                $weeknummer = $datum->formatWeek();
            }

            $overzicht .= $this->parseAanvragen($datum, $eventlijst, $schoonmakers);
        }
        $overzicht .= $this->insertSchoonmaak($schoonmakers);

        return $overzicht;
    }

    protected function maakTapMail(array $events, FoulardDateTime $start): string
    {
        $tapmail = sprintf(
            "%s\n\n%s\n\n%s\n\n%s\n\n",
            $this->config->get('foulard.tapmail.aanhef'),
            $this->config->get('foulard.tapmail.vulling'),
            $this->config->get('foulard.tapmail.afsluiting'),
            $this->config->get('foulard.tapmail.secretaris')
        );

        $tapmail .= $this->maakTapOverzicht($events, $start);

        return $tapmail;
    }
}
