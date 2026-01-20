<?php

namespace App\Forms;

use App\Entities\Ruler;
use App\Repositories\RulerRepository;

class RulerForm extends AbstractForm
{
    public function mapToEntity(): ?Ruler
    {
        if (!$this->validateAllFields()) {
            return null;
        }

        $ruler = new Ruler();
        $ruler->name = $this->data['name'] ?? null;
        $ruler->description = $this->data['description'] ?? null;
        $ruler->categorie = $this->data['categorie'] ?? null;

        return $ruler;
    }


    public function save(): ?Ruler
    {
        $ruler = $this->mapToEntity();

        if ($ruler === null) {
            return null;
        }

        $repository = new RulerRepository();
        $ruler->id = $repository->save($ruler);

        return $ruler;
    }

    public function validateRequiredFields(): bool
    {
        $this->errors = [];

        if (empty($this->data['name'])) {
            $this->errors[] = 'Name is required';
        }
        if (empty($this->data['description'])) {
            $this->errors[] = 'description is required';
        }
        if (empty($this->data['categorie'])) {
            $this->errors[] = 'categorie is required';
        }

        return empty($this->errors);
    }
}
