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
}