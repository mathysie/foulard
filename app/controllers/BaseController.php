<?php

declare(strict_types=1);

namespace app\controllers;

use foulard\google\GoogleAuthenticationException;
use mako\http\routing\Controller;

abstract class BaseController extends Controller
{
    public function beforeAction()
    {
        $this->errorHandler->handle(
            GoogleAuthenticationException::class,
            function (GoogleAuthenticationException $e) {
                http_response_code($e->getCode());

                echo $e->getView()->render();

                return true;
            }
        );
    }

    protected function passFieldErrors(array $errors = []): void
    {
        $this->session->putFlash('errors', $errors);
    }

    protected function getFieldErrors(): array
    {
        return $this->session->getFlash('errors', []);
    }
}
