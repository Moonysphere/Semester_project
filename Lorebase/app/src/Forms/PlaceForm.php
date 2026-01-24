<?php

namespace App\Forms;

use App\Entities\Place;
use App\Repositories\PlaceRepository;

class PlaceForm extends AbstractForm
{
    public function mapToEntity(): ?Place
    {
        if (!$this->validateAllFields()) {
            return null;
        }
        $repository = new PlaceRepository();
        $place = new Place();
        $place->name = $this->data['name'] ?? null;
        $place->slug =$repository->checkSlug("slug","place",$repository->slugify($this->data['name'])) ;
        $place->type = $this->data['type'] ?? null;
        $place->description = $this->data['description'] ?? null;
        $place->status = $this->data['status'] ?? null;

        return $place;
    }

    public function save(): ?Place
    {
        $place = $this->mapToEntity();

        if ($place === null) {
            return null;
        }

        $repository = new PlaceRepository();
        $place->id = $repository->save($place);

        return $place;
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

        if (empty($this->data['description'])) {
            $this->errors[] = 'Description is required';
        }
        if (empty($this->data['status'])) {
            $this->errors[] = 'Status is required';
        }

        return empty($this->errors);
    }
}
