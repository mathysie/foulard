<?php

declare(strict_types=1);

namespace app\routing\middleware;

use Closure;
use mako\http\exceptions\RequestException;
use mako\http\Request;
use mako\http\Response;
use mako\http\routing\middleware\Middleware;
use mako\http\routing\URLBuilder;
use mako\session\Session;
use nuno\models\FeltUser;
use nuno\NunoClient;

class AuthorizationMiddleware extends Middleware
{
    protected $nuno;

    protected $urlBuilder;

    public function __construct(
        NunoClient $nuno,
        URLBuilder $urlBuilder,
        Session $session
    ) {
        $this->nuno = $nuno;
        $this->session = $session;
        $this->urlBuilder = $urlBuilder;
    }

    public function execute(
        Request $request,
        Response $response,
        Closure $next
    ): Response {
        if (!$this->nuno->isLoggedIn()) {
            $response->getHeaders()->add(
                'location',
                $this->urlBuilder->toRoute('login.ask')
            );
            $response->status(302);

            $this->session->putFlash('returnUrl', $request->path());

            return $response;
        }

        $user = $this->nuno->me();

        if (!$this->verify($user)) {
            throw new RequestException(403, 'Onvoldoende rechten.');
        }

        return $next($request, $response);
    }

    protected function verify(FeltUser $me): bool
    {
        return array_key_exists('foobardb', $me->commissies);
    }
}
