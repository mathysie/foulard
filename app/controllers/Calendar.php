<?php

namespace app\controllers;

class Calendar extends BaseController
{
    public function getOverzicht(): string
    {
        $view = $this->view->create('calendar.overzicht');

        return $view->render();
    }
}
