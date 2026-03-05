<?php

namespace App\Repositories;

use App\Lib\Repositories\AbstractRepository;
use App\Entities\Role;

class RoleRepository extends AbstractRepository
{
    public function getRole(int $roleId): ?Role
    {
        return $this->find($roleId);
    }
    public function getRoleName(int $roleId): ?string
    {
        $role = $this->getRole($roleId);
        return $role?->name;
    }

    public function getAllRoles(): array
    {
        return $this->findAll();
    }
}
