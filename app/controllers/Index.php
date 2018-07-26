<?php

namespace app\controllers;

use mako\http\routing\Controller;

/**
 * Welcome controller.
 */
class Index extends BaseController
{
    /**
     * Welcome route.
     *
     * @return string
     */
    public function welcome(): string
    {
        return $this->view->render('base');
    }
}
