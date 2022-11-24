<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\EloquentBaseRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{

    protected function model(): string
    {
        return User::class;
    }

    public function findByEmail(string $email, $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->query()->where('email', $email)->with($relations)->first($columns);
    }

    public function createToken($user, array $abilities = [], DateTimeInterface $expiresAt = null): array
    {
        $token = $user->createToken('api_token_' . $user->id, $abilities, $expiresAt)->plainTextToken;
        return [
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ];
    }
}
