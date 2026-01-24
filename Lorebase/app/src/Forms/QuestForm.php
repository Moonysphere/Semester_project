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

        $quest = new Quest();
        $quest->title = $this->data['title'];
        $quest->description = $this->data['description'];
        $quest->statut_quest = $this->data['statut_quest']; // En cours, Terminé, Pas commencé
        $quest->levelrequirements = (int) ($this->data['levelrequirements']);
        $quest->status = $this->data['status']; // 'draft' ou 'published' pas confondre avec le statut de la quête

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
        if (empty($this->data['statut_quest'])) {
            $this->errors[] = 'Quest statut is required';
        }
        if (!isset($this->data['levelrequirements'])) {
            $this->errors[] = 'Level Requirement is required';
        }
        if (!isset($this->data['status'])) { // Forcer le draft si on choisit rien
            $this->data['status'] = 'draft';
        }
        if (!in_array($this->data['status'], ['draft', 'published'], true)) {
            $this->errors[] = 'Invalid status';
        }

        return empty($this->errors);
    }
}

?>