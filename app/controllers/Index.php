<?php

namespace app\controllers;

use mako\http\response\senders\Redirect;

class Index extends BaseController
{
    public function welcome(): Redirect
    {
        return $this->redirectResponse('calendar.overzicht');
    }
}
