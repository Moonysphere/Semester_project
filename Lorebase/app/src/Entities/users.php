<?php

namespace App\Entities;

use App\Lib\Annotations\ORM\AutoIncrement;
use App\Lib\Annotations\ORM\Column;
use App\Lib\Annotations\ORM\Id;
use App\Lib\Annotations\ORM\ORM;
use App\Lib\Entities\AbstractEntity;

#[ORM]
class users extends AbstractEntity
{

    #[Id]
    #[Column(type: 'varchar', size: 255)]
    public string $email;

    #[Column(type: 'varchar', size: 255)]
    public string $username;

    #[Column(type: 'varchar', size: 255)]
    public string $password;

    #[Column(type: 'varchar', size: 255, nullable: true)]
    public ?string $lastname = null;

    #[Column(type: 'varchar', size: 255, nullable: true)]
    public ?string $firstname = null;

    public function getId(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }
    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }
}
