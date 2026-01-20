<?php

namespace App\Forms;

use App\Entities\Univers;
use App\Repositories\UniversRepository;

class UniversForm extends AbstractForm
{
    protected array $fieldTypes = [
        'name' => 'string',
        'description' => 'optional',
        'createdate' => 'date',
    ];

    public function mapToEntity(): ?Univers
    {
        if (!$this->validateAllFields()) {
            return null;
        }

        $univers = new Univers();
        $univers->name = (string)($this->data['name'] ?? '');
       $univers->description = $this->data['description'] ?? null;
       $value = $this->data['createdate'] ?? null;

if ($value) {
    $value = str_replace('T', ' ', $value);

    if (strlen($value) === 16) {
        $value .= ':00';
    }

    
    $value = substr($value, 0, 10);

    $univers->createdate = new \DateTimeImmutable($value);
} else {
    $univers->createdate = null;
}

        return $univers;
    }

    public function save(): ?Univers
    {
        $univers = $this->mapToEntity();
        if ($univers === null) {
            return null;
        }

        $repository = new UniversRepository();
        $univers->id = (int)$repository->save($univers);

        return $univers;
    }

    public function validateRequiredFields(): bool
    {
        $this->errors = [];

        if (empty($this->data['name'])) {
            $this->errors[] = 'Name is required';
        }

        return empty($this->errors);
    }
}
