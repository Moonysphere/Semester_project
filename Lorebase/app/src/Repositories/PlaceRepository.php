<?php

namespace App\Repositories;

use App\Lib\Repositories\AbstractRepository;

class PlaceRepository extends AbstractRepository
{
    public function getAllPlaces(): array
    {
        return $this->findAll();
    }
}
