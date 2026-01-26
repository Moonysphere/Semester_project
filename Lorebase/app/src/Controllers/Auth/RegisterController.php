<?php

namespace App\Controllers\Auth;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Forms\UserForm;
use App\Repositories\UserRepository;

class RegisterController extends AbstractController
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
        $payload = $request->getPayload();
        $form = new UserForm();

        if (is_string($payload) && !empty($payload)) {
            $parsedData = $form->parseStringToArray($payload);
            if ($parsedData === null || empty($parsedData)) {
                return new Response(
                    json_encode(['success' => false, 'errors' => $form->getErrors()]),
                    400,
                    ['Content-Type' => 'application/json']
                );
            }
            $data = $parsedData;
        }
        if (!is_array($data)) {
            return new Response(
                json_encode(['error' => 'Invalid request format']),
                400,
                ['Content-Type' => 'application/json']
            );
        }
        $form->setData($data);

        if (!$form->validateRegister()) {
            return new Response(
                json_encode(['success' => false, 'errors' => $form->getErrors()]),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $userRepo = new UserRepository();

        if ($userRepo->isUsername($data['username'])) {
            $form->addError('username_taken');
        }

        if ($userRepo->isEmail($data['email'])) {
            $form->addError('email_taken');
        }

        if (!empty($form->getErrors())) {
            return new Response(
                json_encode(['success' => false, 'errors' => $form->getErrors()]),
                409,
                ['Content-Type' => 'application/json']
            );
        }

        $user = $form->toUser();


        $registered = $userRepo->register($user);

        if (!$registered) {
            return new Response(
                json_encode(['success' => false, 'errors' => ['db_error']]),
                500,
                ['Content-Type' => 'application/json']
            );
        }

        $_SESSION['user'] = [
            'username' => $user->username,
            'email' => $user->email,
        ];

        return new Response(
            json_encode(['success' => true]),
            201,
            ['Content-Type' => 'application/json']
        );
    }
}
