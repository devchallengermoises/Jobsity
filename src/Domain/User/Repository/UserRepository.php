<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\User;


class UserRepository
{

    public function __construct()
    {
    }

    public function findBy(string $property, string|int $valueProperty): ?User
    {
        return User::where([$property => $valueProperty])->first();

    }

    public function create(array $properties): ?User
    {
        return User::create($properties);

    }
}
