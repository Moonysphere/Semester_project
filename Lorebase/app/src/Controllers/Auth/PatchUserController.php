<?php

namespace App\Controllers\Auth;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Forms\UserForm;
use App\Repositories\UserRepository;

class PatchUserController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!$this->isLoggedIn()) {
            return new Response(
                json_encode(['success' => false, 'error' => 'not_logged_in']),
                401,
                ['Content-Type' => 'application/json']
            );
        }

        $payload = $request->getPayload();
        $form = new UserForm();


        if (is_string($payload) && !empty($payload)) {
            $data = $form->parseStringToArray($payload);
            if ($data === null || empty($data)) {
                return new Response(
                    json_encode(['success' => false, 'errors' => $form->getErrors()]),
                    400,
                    ['Content-Type' => 'application/json']
                );
            }
        } elseif (is_array($payload)) {
            $data = $payload;
        } else {
            return new Response(
                json_encode(['error' => 'Invalid request format']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $userRepo = new UserRepository();
        $currentUser = $userRepo->findByEmail($_SESSION['user']['email']);

        if (!$currentUser) {
            return new Response(
                json_encode(['success' => false, 'error' => 'user_not_found']),
                404,
                ['Content-Type' => 'application/json']
            );
        }


        $errors = [];


        if (isset($data['username']) && !empty($data['username'])) {
            if (strlen($data['username']) < 3) {
                $errors[] = 'username_too_short';
            } elseif ($data['username'] !== $currentUser->username && $userRepo->isUsername($data['username'])) {
                $errors[] = 'username_taken';
            } else {
                $currentUser->setUsername($data['username']);
            }
        }


        if (isset($data['email']) && !empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'email_invalid';
            } elseif ($data['email'] !== $currentUser->email && $userRepo->isEmail($data['email'])) {
                $errors[] = 'email_taken';
            } else {
                $currentUser->setEmail($data['email']);
            }
        }


        if (isset($data['firstname']) && !empty($data['firstname'])) {
            $currentUser->setFirstname($data['firstname']);
        }


        if (isset($data['lastname']) && !empty($data['lastname'])) {
            $currentUser->setLastname($data['lastname']);
        }

        if (isset($data['password']) && !empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                $errors[] = 'password_too_short';
            } elseif (!isset($data['current_password']) || !password_verify($data['current_password'], $currentUser->password)) {
                $errors[] = 'current_password_incorrect';
            } else {
                $currentUser->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
            }
        }

        if (!empty($errors)) {
            return new Response(
                json_encode(['success' => false, 'errors' => $errors]),
                400,
                ['Content-Type' => 'application/json']
            );
        }


        try {
            $userRepo->updateUser($currentUser);


            $_SESSION['user']['username'] = $currentUser->username;
            $_SESSION['user']['email'] = $currentUser->email;

            return new Response(
                json_encode([
                    'success' => true,
                    'message' => 'profile_updated',
                    'user' => [
                        'username' => $currentUser->username,
                        'email' => $currentUser->email,
                        'firstname' => $currentUser->firstname,
                        'lastname' => $currentUser->lastname,
                    ]
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            error_log('Update user error: ' . $e->getMessage());
            return new Response(
                json_encode(['success' => false, 'error' => 'db_error']),
                500,
                ['Content-Type' => 'application/json']
            );
        }
    }
}
