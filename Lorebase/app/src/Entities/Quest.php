<?php

namespace App\Entities;

use App\Lib\Annotations\ORM\AutoIncrement;
use App\Lib\Annotations\ORM\Id;
use App\Lib\Annotations\ORM\Column;

class Quest
{
    #[Id]
    #[AutoIncrement]
    #[Column(type: 'int')]
    private ?int $id = null;

    #[Column(type: 'varchar', size: 255)]
    private string $title;

    #[Column(type: 'text')]
    private string $description;

    #[Column(type: 'varchar', size: 50, default: 'draft')]
    private string $statut;

    #[Column(type: 'int', nullable: true)]
    private ?int $startPlaceId = null;

    #[Column(type: 'int')]
    private int $universeId;

    #[Column(type: 'int', default: 0)]
    private int $levelRequirement = 0;

    public function __construct(
        string $title,
        string $description,
        string $statut,
        ?int $startPlaceId,
        int $universeId,
        int $levelRequirement = 0
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->statut = $statut;
        $this->startPlaceId = $startPlaceId;
        $this->universeId = $universeId;
        $this->levelRequirement = $levelRequirement;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getStartPlaceId(): ?int
    {
        return $this->startPlaceId;
    }

    public function getUniverseId(): int
    {
        return $this->universeId;
    }

    public function getLevelRequirement(): int
    {
        return $this->levelRequirement;
    }
}