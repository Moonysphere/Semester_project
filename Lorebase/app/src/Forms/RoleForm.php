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
        $repository = new RoleRepository();
        $role = new Role();
        $role->name = $this->data['name'] ?? null;
        $role->slug = $repository->checkSlug("slug", "role", $repository->slugify($this->data['name']));
        $role->description = $this->data['description'] ?? null;
        $role->status = $this->data['status'];

        return $role;
    }


    public function save(): ?Role
    {
        $role = $this->mapToEntity();

        if ($role === null) {
            return null;
        }

        $repository = new RoleRepository();
        $role->id = $repository->save($role);

        return $role;
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
        if (!isset($this->data['status'])) { // Forcer le draft si on choisit rien
            $this->data['status'] = 'draft';
        }
        if (!in_array($this->data['status'], ['draft', 'published', 'archived'], true)) {
            $this->errors[] = 'Invalid status';
        }
        return empty($this->errors);
    }
}
