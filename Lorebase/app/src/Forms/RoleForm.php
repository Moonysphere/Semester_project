<?php

namespace App\Forms;

use App\Entities\Role;
use App\Repositories\RoleRepository;

class RoleForm extends AbstractForm
{
    public function mapToEntity(): ?Role
    {
        if (!$this->validateAllFields()) {
            return null;
        }

        $role = new Role();
        $role->name = $this->data['name'] ?? null;
        $role->description = $this->data['description'] ?? null;

        return $role;
    }


    public function save(): ?Role
    {
        $ruler = $this->mapToEntity();

        if ($ruler === null) {
            return null;
        }

        $repository = new RoleRepository();
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
        return empty($this->errors);
    }
}
