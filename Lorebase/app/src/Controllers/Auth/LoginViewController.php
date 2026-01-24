<?php

namespace App\Controllers\Auth;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class LoginViewController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($this->isLoggedIn()) {
            return new Response('', 302, ['Location' => '/']);
        }

        return $this->render('auth', 'login');
    }
}
