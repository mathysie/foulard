<?php

namespace app\controllers;

use mako\http\response\senders\Redirect;

class Index extends BaseController
{
    public function getIndex(): Redirect
    {
        return $this->redirectResponse('tapschema.tapmail');
    }
}
