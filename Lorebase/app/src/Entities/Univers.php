<?php

namespace App\Entities;

use App\Lib\Annotations\ORM\Column;
use App\Lib\Annotations\ORM\Id;
use App\Lib\Annotations\ORM\ORM;
use App\Lib\Entities\AbstractEntity;

#[ORM]
class Univers extends AbstractEntity
{
    #[Id]
    #[Column(type: 'serial')]
    public int $id;

    #[Column(type: 'varchar', size: 255)]
    public string $name;

    #[Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[Column(type: 'date', nullable: true)]
    public ?\DateTimeImmutable $createdate = null;

    #[Column(type:'varchar', size: 255)]
    public string $status;

    /* =======================
       GETTERS
    ======================= */

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreateDate(): ?\DateTimeImmutable
    {
        return $this->createdate;
    }

    /* =======================
       SETTERS
    ======================= */

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setCreateDate(?\DateTimeImmutable $createdate): void
    {
        $this->createdate = $createdate;
    }
}
