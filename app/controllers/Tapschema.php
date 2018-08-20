<?php

declare(strict_types=1);

namespace app\controllers;

use DateInterval;
use overhemd\calendar\events\AanvraagEvent;
use overhemd\calendar\events\aanvragen\borrels\AegirBorrel;
use overhemd\calendar\events\aanvragen\borrels\TappersBedankBorrel;
use overhemd\calendar\events\SchoonmaakEvent;
use overhemd\datetime\OverhemdDateTime;

class Tapschema extends BaseController
{
    public function getTapmail(int $offset = 0): string
    {
        $start = $this->getStart($offset);
        $end = $this->getEnd($start);

        $events = $this->calendarHelper->getEvents($start, $end);
        $tapmail = $this->maakTapMail($events, $start);
        $rows = substr_count($tapmail, "\n") + 1;

        $view = $this->view->create('tapschema.tapmail');

        $view->assign('start', $start);
        $view->assign('end', $end);
        $view->assign('eerder', $offset - 1);
        $view->assign('later', $offset + 1);
        $view->assign('tapmail', $tapmail);
        $view->assign('rows', $rows);

        return $view->render();
    }

    protected function getStart(int $offset): OverhemdDateTime
    {
        $start = new OverhemdDateTime('next Monday');

        if ($offset > 0) {
            $start->add(new DateInterval("P{$offset}W"));
        } elseif ($offset < 0) {
            $offset = abs($offset);
            $start->sub(new DateInterval("P{$offset}W"));
        }

        return $start;
    }

    protected function getEnd(OverhemdDateTime $start): OverhemdDateTime
    {
        return new OverhemdDateTime(sprintf(
            '%d Sundays after %s',
            $this->config->get('overhemd.tapmail.weken'),
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
        return sprintf("%s\n\n", $this->config->get('overhemd.tapmail.scheiding'));
    }

    protected function parseAanvragen(
        OverhemdDateTime $datum,
        array $eventlijst,
        string &$schoonmakers
    ): string {
        $tekst = '';
        $aanvragenlijst = [];
        $overige_borrels = [];
        $tappers = [];
        $tap_min = null;

        foreach ($eventlijst as $event) {
            if ($event instanceof AanvraagEvent) {
                $tappers = array_merge($tappers, $event->tappers);
                $tap_min = $event->tap_min > $tap_min ? $event->tap_min
                            : $tap_min;
                $this->editAanvragenLijst(
                    $aanvragenlijst,
                    $overige_borrels,
                    $event
                );
            } elseif ($event instanceof SchoonmaakEvent) {
                $schoonmakers = explode('S: ', $event->event->summary, 2)[1];
            }
        }

        if (!empty($aanvragenlijst)) {
            if (count($tappers) < $tap_min) {
                $tappers[] = 'wie?';
            }
            $tekst = sprintf(
                "%s: %s - %s\n\n",
                $datum->formatTapmail(),
                implode(' + ', $aanvragenlijst),
                implode(', ', $tappers)
            );
        }

        return $tekst;
    }

    protected function editAanvragenLijst(
        array &$aanvragenlijst,
        array &$overige_borrels,
        AanvraagEvent $event
    ): void {
        foreach ($event->aanvragen as $aanvraag) {
            if ($aanvraag instanceof AegirBorrel || $aanvraag instanceof TappersBedankBorrel) {
                $overige_borrels[] = $aanvraag->getTitel();
            } elseif ($aanvraag instanceof RegulierBorrel) {
                array_push($aanvragenlijst, $aanvraag->getTitel());
            } else {
                $aanvragenlijst[] = $aanvraag->getTitel();
            }
        }
    }

    protected function maakTapOverzicht(array $events, OverhemdDateTime $start): string
    {
        $overzicht = '';
        $weeknummer = $start->formatWeek();
        $schoonmakers = '';

        $overzicht .= $this->insertScheiding();
        foreach ($events as $datum => $eventlijst) {
            $datum = new OverhemdDateTime($datum);
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

    protected function maakTapMail(array $events, OverhemdDateTime $start): string
    {
        $tapmail = sprintf(
            "%s\n\n%s\n\n%s\n\n%s\n\n",
            $this->config->get('overhemd.tapmail.aanhef'),
            $this->config->get('overhemd.tapmail.vulling'),
            $this->config->get('overhemd.tapmail.afsluiting'),
            $this->config->get('overhemd.tapmail.secretaris')
        );

        $tapmail .= $this->maakTapOverzicht($events, $start);

        return $tapmail;
    }
}
