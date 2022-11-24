<?php

namespace App\Repositories\Contracts;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email, $columns = ['*'], array $relations = []): ?Model;

    public function createToken($user, array $abilities = ['*'], DateTimeInterface $expiresAt = null): array;
}
