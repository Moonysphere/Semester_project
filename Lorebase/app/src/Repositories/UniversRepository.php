<?php

namespace App\Repositories;

use App\Lib\Repositories\AbstractRepository;

class UniversRepository extends AbstractRepository
{

    public function getAllUniverses(): array
    {
        return $this->findAll();
    }
}
