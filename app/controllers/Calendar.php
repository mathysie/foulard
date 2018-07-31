<?php

namespace app\controllers;

class Calendar extends BaseController
{
    public function getIndex(): string
    {
        $view = $this->view->create('calendar.index');

        return $view->render();
    }
}
