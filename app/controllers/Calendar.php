<?php

namespace app\controllers;

use DateTime;

class Calendar extends BaseController
{
    public function getOverzicht(): string
    {
        $view = $this->view->create('calendar.overzicht');

        $events = $this->calendarHelper->getEvents();
        $events = $this->calendarParser->parseEvents($events);

        $view->assign('tapmail', $this->maakTapMail($events));

        return $view->render();
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

    protected function maakTapOverzicht(array $events): string
    {
        $start = new DateTime('2018-08-06');

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

    protected function maakTapMail(array $events): string
    {
        $tapmail = sprintf(
            "%s\n\n%s\n\n%s\n\n%s\n\n",
            $this->config->get('foulard.tapmail.aanhef'),
            $this->config->get('foulard.tapmail.vulling'),
            $this->config->get('foulard.tapmail.afsluiting'),
            $this->config->get('foulard.tapmail.secretaris')
        );

        $tapmail .= $this->maakTapOverzicht($events);

        return $tapmail;
    }
}
