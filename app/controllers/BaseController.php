<?php

declare(strict_types=1);

namespace app\controllers;

use foulard\google\GoogleAuthenticationException;
use mako\http\routing\Controller;
use mako\view\View;

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

    protected function getFieldErrors(View &$view): void
    {
        $errors = $this->session->getFlash('errors', []);
        $view->assign('errors', $errors);
    }

    protected function passSuccess(): void
    {
        $this->session->putFlash('success', true);
    }

    protected function getSuccess(View &$view): void
    {
        $success = $this->session->getFlash('success', false);
        $view->assign('success', $success);
    }
}
