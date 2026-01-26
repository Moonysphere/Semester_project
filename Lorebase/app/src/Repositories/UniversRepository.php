<?php

namespace App\Repositories;

use App\Lib\Repositories\AbstractRepository;
use App\Entities\Univers;

class UniversRepository extends AbstractRepository
{
    public function getUnivers(int $universId): ?Univers
    {
        return $this->find($universId);
    }
    public function getUniversName(int $universId): ?string
    {
        $univers = $this->getUnivers($universId);
        return $univers?->name;
    }

    public function getAllUniverses(): array
    {
        return $this->findAll();
    }
}
