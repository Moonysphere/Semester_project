<?php

namespace App\Forms;

use App\Entities\Univers;
use App\Repositories\UniversRepository;

class UniversForm extends AbstractForm
{
    public function mapToEntity(): ?Univers
    {
        if (!$this->validateAllFields()) {
            return null;
        }

        $univers = new Univers();
        $univers->name = $this->data['name'] ?? null;
        $univers->description = $this->data['type'] ?? null;
        $univers->createDate = $this->data['origin'] ?? null;

        return $univers;
    }

    public function save(): ?Univers
    {
        $univers = $this->mapToEntity();

        if ($univers === null) {
            return null;
        }

        $repository = new UniversRepository();
        $univers->id = $repository->save($univers);

        return $univers;
    }

    public function validateRequiredFields(): bool
    {
        $this->errors = [];

        if (empty($this->data['name'])) {
            $this->errors[] = 'Name is required';
        }

        if (empty($this->data['type'])) {
            $this->errors[] = 'Type is required';
        }

        return empty($this->errors);
    }
}
