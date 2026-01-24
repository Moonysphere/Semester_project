<?php

namespace App\Forms;

class LoginForm extends AbstractForm
{

    public function validate(): bool
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
}
