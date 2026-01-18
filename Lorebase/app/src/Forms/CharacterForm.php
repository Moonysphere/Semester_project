<?php

namespace App\Forms;

use App\Entities\Character;
use App\Repositories\CharacterRepository;

class CharacterForm extends AbstractForm
{
    public function mapToEntity(): ?Character
    {
        if (!$this->validateAllFields()) {
            return null;
        }

        $character = new Character();
        $character->name = $this->data['name'] ?? null;
        $character->role = $this->data['role'] ?? null;
        $character->origin = $this->data['origin'] ?? null;
        $character->pv = (int)($this->data['pv'] ?? 0);
        $character->description = $this->data['description'] ?? null;

        return $character;
    }


    public function save(): ?Character
    {
        $character = $this->mapToEntity();

        if ($character === null) {
            return null;
        }

        $repository = new CharacterRepository();
        $character->id = $repository->save($character);

        return $character;
    }

    public function validateRequiredFields(): bool
    {
        $this->errors = [];

        if (empty($this->data['name'])) {
            $this->errors[] = 'Name is required';
        }
        if (empty($this->data['role'])) {
            $this->errors[] = 'Role is required';
        }

        return empty($this->errors);
    }
}
