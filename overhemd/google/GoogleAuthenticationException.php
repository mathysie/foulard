<?php

declare(strict_types=1);

namespace overhemd\google;

use mako\application\Application;
use mako\http\exceptions\RequestException;
use mako\view\View;
use mako\view\ViewFactory;

class GoogleAuthenticationException extends RequestException
{
    /** @var View */
    private $view;

    public function __construct(string $message = null, Throwable $previous = null)
    {
        parent::__construct(401, $message, $previous);

        $this->view = Application::instance()->getContainer()
                        ->get(ViewFactory::class)
                        ->create('exceptions.authentication-exception');
    }

    public function getView(): View
    {
        return $this->view;
    }
}
