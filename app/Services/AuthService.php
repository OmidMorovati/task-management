<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function register(string $name, string $email, string $password): array
    {
        $user = $this->userRepository->store([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password)
        ]);

        return $this->userRepository->createToken($user);
    }

    public function login(string $email, string $password): ?array
    {
        /** @var User $user */
        $user = $this->userRepository->findByEmail($email);
        if (!isset($user) || !Hash::check($password, $user->password)) {
            return null;
        }

        return $this->userRepository->createToken($user);
    }

    public function me(): ?Model
    {
        if (Auth::guest()) {
            return null;
        }
        return $this->userRepository->find(Auth::id(), ['name', 'email']);
    }

    public function logout(): bool
    {
       return Auth::user()->currentAccessToken()->delete();
    }
}
