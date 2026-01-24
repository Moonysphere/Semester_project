<?php

namespace App\Forms;

use App\Entities\Quest;
use App\Repositories\QuestRepository;

class QuestForm extends AbstractForm
{
    public function mapToEntity(): ?Quest
    {
        if (!$this->validateAllFields()) {
            return null;
        }
        $repository = new QuestRepository();
        $quest = new Quest();
        $quest->title = $this->data['title'];
        $quest->slug =$repository->checkSlug("slug","quest",$repository->slugify($this->data['title'])) ;
        $quest->description = $this->data['description'];
        $quest->statut = $this->data['statut'];
        $quest->levelrequirements = (int) ($this->data['levelrequirements']);

        return $quest;
    }

    public function save(): ?Quest
    {
        $quest = $this->mapToEntity();

        if ($quest === null) {
            return null;
        }

        $repository = new QuestRepository();
        $quest->id = $repository->save($quest);

        return $quest;
    }

    public function validateRequiredFields(): bool
    {
        $this->errors = [];

        if (empty($this->data['title'])) {
            $this->errors[] = 'Title is required';
        }
        if (empty($this->data['description'])) {
            $this->errors[] = 'Description is required';
        }
        if (empty($this->data['statut'])) {
            $this->errors[] = 'Statut is required';
        }
        if (!isset($this->data['levelrequirements'])) {
            $this->errors[] = 'Level Requirement is required';
        }

        return empty($this->errors);
    }
}

?>