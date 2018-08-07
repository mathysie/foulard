<?php

namespace app\controllers;

use foulard\datetime\FoulardDateTime;

class Calendar extends BaseController
{
    public function getOverzicht()
    {
        $start = new FoulardDateTime($_GET['start'] ?? null);
        $end = new FoulardDateTime($_GET['end'] ?? '5 years');
        $params = [
            'timeMin' => $start->formatGoogle(),
            'timeMax' => $end->formatGoogle(),
        ];
        $view = $this->view->create('calendar.overzicht');

        $view->assign('start', $start);
        $view->assign('end', $end);

        return $view->render();
    }
}
