<?php

declare(strict_types=1);

namespace app\controllers;

use mako\http\routing\Controller;

abstract class BaseController extends Controller
{
    protected function passFieldErrors(array $errors = []): void
    {
        $this->session->putFlash('errors', $errors);
    }

    protected function getFieldErrors(): array
    {
        return $this->session->getFlash('errors', []);
    }
}
