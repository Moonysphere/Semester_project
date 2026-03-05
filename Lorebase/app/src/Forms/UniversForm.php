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
        'status' => 'string',
    ];


    public function mapToEntity(): ?Univers
    {
        if (!$this->validateAllFields()) {
            return null;
        }
        $repository = new UniversRepository();
        $univers = new Univers();
        $univers->name = (string)($this->data['name'] ?? '');
        $univers->description = $this->data['description'] ?? null;
        $value = $this->data['createdate'] ?? null;
        $univers->status = $this->data['status']; // 'draft' ou 'published' pas confondre avec le statut de la quête
        $univers->slug = $repository->checkSlug("slug", "univers", $repository->slugify($this->data['name']));


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
        $univers->user_id = $this->data['user_id'] ?? null;

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
        if (empty($this->data['status'])) {
            $this->errors[] = 'Status is required';
        }

        return empty($this->errors);
    }
}
