<?php

namespace App\Controllers\Auth;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class RegisterViewController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->render('auth', 'register');
    }
}
