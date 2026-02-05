<?php

namespace App\Entities;

use App\Lib\Annotations\ORM\AutoIncrement;
use App\Lib\Annotations\ORM\Column;
use App\Lib\Annotations\ORM\Id;
use App\Lib\Annotations\ORM\ORM;
use App\Lib\Entities\AbstractEntity;
use App\Lib\Annotations\ORM\References;

#[ORM]
class Place extends AbstractEntity
{
    #[Id]
    #[AutoIncrement]
    #[Column(type: 'int')]
    public int $id;

    #[Column(type: 'varchar', size: 255)]
    public string $name;

    #[Column(type: 'varchar', size: 255)]
    public string $slug;

    #[Column(type: 'varchar', size: 255)]
    public string $type;

    #[Column(type: 'varchar', size: 255)]
    public string $description;

    #[Column(type: 'varchar', size: 255)]
    public string $status;

    #[Column(type: 'int')]
    #[References(class: Univers::class, property: 'id')]
    public int $univers_id;

    #[Column(type: 'varchar', size: 255, nullable: true)]
    #[References(class: users::class, property: 'email')]
    public ?string $user_id = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getSlug(): string
    {
        return $this->slug;
    }


    public function getType(): string
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
