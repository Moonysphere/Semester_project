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
        $repository = new CharacterRepository();
        $character = new Character();
        $character->name = $this->data['name'] ?? null;
        $character->slug =$repository->checkSlug("slug","character",$repository->slugify($this->data['name'])) ;
        $character->role = $this->data['role'] ?? null;
        $character->origin = $this->data['origin'] ?? null;
        $character->pv = (int)($this->data['pv'] ?? 0);
        $character->description = $this->data['description'] ?? null;
        $character->status = $this->data['status'] ?? null;

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
        if (empty($this->data['status'])) {
            $this->errors[] = 'Status is required';
        }

        return empty($this->errors);
    }
}
