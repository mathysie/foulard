<?php

namespace app\controllers;

use DateInterval;
use DateTime;

class Calendar extends BaseController
{
    public function getOverzicht(): string
    {
        $start = $this->getStart($offset);
        $end = $this->getEnd($start);

        $events = $this->calendarHelper->getEvents($start, $end);
        $events = $this->calendarParser->parseEvents($events);
        $tapmail = $this->maakTapMail($events, $start);
        $rows = substr_count($tapmail, "\n") + 1;

        $view = $this->view->create('calendar.overzicht');

        $view->assign('start', strftime('%e %B', $start->getTimestamp()));
        $view->assign('end', strftime('%e %B', $end->getTimestamp()));
        $view->assign('tapmail', $tapmail);
        $view->assign('rows', $rows);

        return $view->render();
    }

    protected function getStart(int $offset): DateTime
    {
        $start = new DateTime('next Monday');

        if ($offset > 0) {
            $start->add(new DateInterval("P{$offset}W"));
        } elseif ($offset < 0) {
            $offset = abs($offset);
            $start->sub(new DateInterval("P{$offset}W"));
        }

        return $start;
    }

    protected function getEnd(DateTime $start): DateTime
    {
        return new DateTime(sprintf(
            '%d Sundays after %s',
            $this->config->get('foulard.tapmail.weken'),
            $start->format('Y-m-d')
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
        DateTime $datum,
        array $eventlijst,
        string &$schoonmakers
    ): string {
        $tekst = '';
        $aanvragenlijst = [];
        $tappers = '';

        foreach ($eventlijst as $event) {
            switch ($event->type) {
                case 'aanvraag':
                    $summary = explode(' - ', $event->event->summary, 2);
                    array_unshift($aanvragenlijst, $summary[0]);
                    if (isset($summary[1])) {
                        $tappers = $summary[1];
                    }
                    break;

                case 'aegir':
                case 'tappersbedank':
                    $aanvragenlijst[] = $event->event->summary;
                    break;

                case 'schoonmaak':
                    $schoonmakers = explode('S: ', $event->event->summary, 2)[1];
                    break;

                case 'overig':
                case 'vergadering':
                    break;
            }
        }

        if (!empty($aanvragenlijst)) {
            $tekst = sprintf(
                "%s: %s - %s\n\n",
                strftime('%a %e %b', $datum->getTimestamp()),
                implode(' + ', $aanvragenlijst),
                !empty($tappers) ? $tappers : 'wie?'
            );
        }

        return $tekst;
    }

    protected function maakTapOverzicht(array $events, DateTime $start): string
    {
        $overzicht = '';
        $weeknummer = $start->format('W');
        $schoonmakers = '';

        $overzicht .= $this->insertScheiding();
        foreach ($events as $datum => $eventlijst) {
            $datum = new DateTime($datum);
            if ($datum->format('W') !== $weeknummer) {
                $overzicht .= $this->insertSchoonmaak($schoonmakers);
                $overzicht .= $this->insertScheiding();
                $weeknummer = $datum->format('W');
            }

            $overzicht .= $this->parseAanvragen($datum, $eventlijst, $schoonmakers);
        }
        $overzicht .= $this->insertSchoonmaak($schoonmakers);

        return $overzicht;
    }

    protected function maakTapMail(array $events, DateTime $start): string
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
