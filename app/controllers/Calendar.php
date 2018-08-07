<?php

namespace app\controllers;

use foulard\calendar\events\AanvraagEvent;
use foulard\datetime\FoulardDateTime;

class Calendar extends BaseController
{
    public function getOverzicht()
    {
        $start = $this->getStart();
        $einde = $this->getEinde($start);
        $params = [
            'timeMin' => $start->formatGoogle(),
            'timeMax' => $einde->formatGoogle(),
        ];
        $events = $this->calendarHelper->getEvents($start, $einde, $params);
        $events = $this->calendarParser->parseEvents($events, AanvraagEvent::class);
        $view = $this->view->create('calendar.overzicht');

        $view->assign('start', $start);
        $view->assign('einde', $einde);
        $view->assign('events', $events);

        return $view->render();
    }

    protected function getStart(): FoulardDateTime
    {
        if (isset($_GET['start'])) {
            return new FoulardDateTime($_GET['start']);
        } else {
            return new FoulardDateTime();
        }
    }

    protected function getEinde(FoulardDateTime $start): FoulardDateTime
    {
        if (isset($_GET['einde'])) {
            return new FoulardDateTime($_GET['einde']);
        } else {
            return new FoulardDateTime("{$start} + 1 year");
        }
    }
}
