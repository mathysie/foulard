<?php

declare(strict_types=1);

namespace app\controllers;

use mako\http\response\senders\Redirect;
use nuno\exception\auth\InvalidCredentialsException;
use nuno\exception\InvalidStateException;
use nuno\exception\MembershipFeeException;
use Throwable;

class Login extends BaseController
{
    public function askLogin()
    {
        if (null === $this->session->getFlash('returnUrl')) {
            $returnUrl = $this->request->referer('/');
        } else {
            $returnUrl = $this->session->getFlash('returnUrl');
        }

        if ($this->nuno->isLoggedIn()) {
            return $this->redirectResponse($returnUrl);
        }

        $this->session->putFlash('returnUrl', $returnUrl);

        $view = $this->view->create('login.form');

        $this->getFieldErrors($view);

        return $view->render();
    }

    public function processLogin(): Redirect
    {
        $gebruikersnaam = $_POST['gebruikersnaam'];
        $wachtwoord = $_POST['wachtwoord'];

        $returnUrl = $this->session->getFlash('returnUrl', '/');
        $this->session->putFlash('returnUrl', $returnUrl);

        try {
            if ($this->nuno->login($gebruikersnaam, $wachtwoord)) {
                return $this->redirectResponse($returnUrl);
            } else {
                return $this->redirectResponse('login.ask');
            }
        } catch (InvalidCredentialsException $e) {
            $this->passFieldErrors(['Ongeldige gebruikersnaam of wachtwoord']);

            return $this->redirectResponse('login.ask');
        } catch (MembershipFeeException $e) {
            $this->passFieldErrors(['Je moet nog contributie betalen']);

            return $this->redirectResponse('login.ask');
        } catch (InvalidStateException $e) {
            if ('User is already logged in.' == $e->getMessage()) {
                $this->passFieldErrors(['Je bent al ingelogd']);
            } else {
                $this->passFieldErrors(['Iets is fout gegaan. Blame het ICT.']);
            }

            return $this->redirectResponse('login.ask');
        } catch (Throwable $e) {
            // Stack trace contains username/password, so avoid logging that.
            $this->logger->error(sprintf('Caught %s: %s', get_class($e), $e->getMessage()));
            $this->passFieldErrors(['Iets is fout gegaan. Blame het ICT.']);

            return $this->redirectResponse('login.ask');
        }
    }

    public function logout(): Redirect
    {
        if (!$this->nuno->isLoggedIn()) {
            return $this->redirectResponse($this->request->referer());
        }

        $this->nuno->logout();

        return $this->redirectResponse($this->request->referer());
    }
}
