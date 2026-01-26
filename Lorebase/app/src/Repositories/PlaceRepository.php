<?php

namespace App\Repositories;

use App\Lib\Repositories\AbstractRepository;
use App\Entities\Place;

class PlaceRepository extends AbstractRepository
{
    public function getPlace(int $placeId): ?Place
    {
        return $this->find($placeId);
    }
    public function getPlaceName(int $placeId): ?string
    {
        $place = $this->getPlace($placeId);
        return $place?->name;
    }

    public function getAllPlaces(): array
    {
        return $this->findAll();
    }
}
