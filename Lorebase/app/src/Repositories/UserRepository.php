<?php

namespace App\Repositories;

use App\Lib\Repositories\AbstractRepository;
use App\Entities\users;

class UserRepository extends AbstractRepository
{
    public function getTable(): string
    {
        return 'users';
    }

    public function isUsername(string $username): bool
    {
        $isUsername = $this->findBy(['username' => $username]);
        return !empty($isUsername);
    }

    public function isEmail(string $email): bool
    {
        $isEmail = $this->findBy(['email' => $email]);
        return !empty($isEmail);
    }

    public function findByUsername(string $username): ?users
    {
        $results = $this->findBy(['username' => $username]);
        return $results[0] ?? null;
    }

    public function findByEmail(string $email): ?users
    {
        $results = $this->findBy(['email' => $email]);
        return $results[0] ?? null;
    }

    public function register(users $user): bool
    {
        try {
            $this->queryBuilder()
                ->insert($user)
                ->values($user)
                ->setParams($user->toArray());

            $this->executeQuery();
            return true;
        } catch (\Exception $e) {
            error_log('Register error: ' . $e->getMessage());

            return false;
        }
    }
}
