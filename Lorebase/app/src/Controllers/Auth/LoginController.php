<?php

namespace App\Controllers\Auth;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Forms\UserForm;
use App\Repositories\UserRepository;

class LoginController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($this->isLoggedIn()) {
            return new Response(
                json_encode(['success' => false, 'errors' => ['already_logged_in']]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $form = new UserForm();
        $payload = $request->getPayload();

        if (is_string($payload) && !empty($payload)) {
            $data = $form->parseStringToArray($payload);
            if ($data === null) {
                return new Response(
                    json_encode(['success' => false, 'errors' => $form->getErrors()]),
                    400,
                    ['Content-Type' => 'application/json']
                );
            }
            $form->setData($data);
        } elseif (is_array($payload) && !empty($payload)) {
            $form->setData($payload);
        } else {
            $form->setData($_POST);
        }

        if (!$form->validateLogin()) {
            return new Response(
                json_encode(['success' => false, 'errors' => $form->getErrors()]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $userRepo = new UserRepository();
        $data = $form->getData();

        $user = $userRepo->findByUsername($data['username']);

        if ($user === null) {
            return new Response(
                json_encode(['success' => false, 'errors' => ['user_not_found']]),
                401,
                ['Content-Type' => 'application/json']
            );
        }

        if (!password_verify($data['password'], $user->password)) {
            return new Response(
                json_encode(['success' => false, 'errors' => ['invalid_password']]),
                401,
                ['Content-Type' => 'application/json']
            );
        }

        $_SESSION['user'] = [
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
        ];

        return new Response(
            json_encode(['success' => true, 'user' => $_SESSION['user']]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
