<?php

namespace app\controllers;

use DateTime;

class Calendar extends BaseController
{
    public function getOverzicht()
    {
        $start = new DateTime($_GET['start'] ?? null);
        $end = new DateTime($_GET['end'] ?? '5 years');
        $params = [
            'timeMin' => $start->format(DateTime::RFC3339),
            'timeMax' => $end->format(DateTime::RFC3339),
        ];
        $view = $this->view->create('calendar.overzicht');

        $view->assign('start', $start);
        $view->assign('end', $end);

        return $view->render();
    }
}
