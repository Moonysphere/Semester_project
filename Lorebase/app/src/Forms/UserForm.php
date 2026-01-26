<?php

namespace App\Forms;

use App\Entities\users;

class UserForm extends AbstractForm
{

    public function validateLogin(): bool
    {
        $this->errors = [];

        if (empty($this->data['username'])) {
            $this->errors[] = 'username_required';
        }

        if (empty($this->data['password'])) {
            $this->errors[] = 'password_required';
        }

        return empty($this->errors);
    }

    protected array $errors = [];

    private function sanitize(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', trim($value));
    }


    public function validateRegister(): bool
    {
        $this->errors = [];

        if (empty($this->data['username'])) {
            $this->errors[] = 'username_required';
        }

        if (empty($this->data['email'])) {
            $this->errors[] = 'email_required';
        } elseif (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'email_invalid';
        }

        if (empty($this->data['password'])) {
            $this->errors[] = 'password_required';
        } elseif (strlen($this->data['password']) < 8) {
            $this->errors[] = 'password_too_short';
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }


    public function toUser(): users
    {
        $user = new users();

        $user->setUsername($this->sanitize($this->data['username']));
        $user->setEmail(trim($this->data['email']));
        $user->setPassword(password_hash($this->data['password'], PASSWORD_BCRYPT));
        $user->setFirstname($this->sanitize($this->data['firstname'] ?? ''));
        $user->setLastname($this->sanitize($this->data['lastname'] ?? ''));
        $user->role = 'author';

        return $user;
    }
}
