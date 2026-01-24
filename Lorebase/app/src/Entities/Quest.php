<?php

namespace App\Entities;

use App\Lib\Annotations\ORM\AutoIncrement;
use App\Lib\Annotations\ORM\Id;
use App\Lib\Annotations\ORM\Column;
use App\Lib\Annotations\ORM\ORM;
use App\Lib\Entities\AbstractEntity;

#[ORM]
class Quest extends AbstractEntity
{
    #[Id]
    #[AutoIncrement]
    #[Column(type: 'int')]
    public int $id;

    #[Column(type: 'varchar', size: 255)]
    public string $title;

    #[Column(type: 'varchar', size: 255)]
    public string $slug;

    #[Column(type: 'text')]
    public string $description;

    #[Column(type: 'varchar', size: 50)]
    public string $statut_quest;

    #[Column(type: 'int')]
    public int $levelrequirements;

    #[Column(type:'varchar', size: 255)]
    public string $status;


    public function getId(): int
    {
        return $this->id;
    }

    // TITLE
public function getTitle(): string
{
    return $this->title;
}

public function setTitle(string $title): self
{
    $this->title = $title;
    return $this;
}

// SLUG
public function getSlug(): string
{
    return $this->slug;
}

public function setSlug(string $slug): self
{
    $this->slug = $slug;
    return $this;
}

// DESCRIPTION
public function getDescription(): string
{
    return $this->description;
}

public function setDescription(string $description): self
{
    $this->description = $description;
    return $this;
}

// STATUT
public function getStatut(): string
{
    return $this->statut;
}

public function setStatut(string $statut): self
{
    $this->statut = $statut;
    return $this;
}

// LEVEL REQUIREMENTS
public function getLevelrequirements(): int
{
    return $this->levelrequirements;
}

public function setLevelrequirements(int $levelrequirements): self
{
    $this->levelrequirements = $levelrequirements;
    return $this;
}

}