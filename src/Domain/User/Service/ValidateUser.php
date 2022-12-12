<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserRepository;


class ValidateUser
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login($data): array
    {
        $errors = [];

        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'invalid email';
        }

        if (!isset($data['password'])) {
            $errors[] = 'invalid password';
        }
        $user = $this->repository->findBy('email', $data['email'] ?? 'email');


        if (!$errors && !$user) {
            $errors[] = 'invalid credentials';
        }

        if (!$errors && !password_verify($data['password'], $user->password)) {
            $errors[] = 'Invalid credentials';
        }

        if ($errors) {
            return [
                'success' => false,
                'data' => $errors
            ];
        }

        return [
            'success' => true,
            'data' => $user
        ];
    }

    public function create($data): array
    {
        $errors = [];
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'invalid email';
        }

        if (!isset($data['password'])) {
            $errors[] = 'invalid password';
        }

        $userExists = $this->repository->findBy('email', $data['email'] ?? 'email');

        if (!$errors && $userExists) {
            $errors[] = 'email already exists';
        }
        if ($errors) {
            return [
                'success' => false,
                'data' => $errors
            ];
        }

        $user = $this->repository->create($data);

        return [
            'success' => true,
            'data' => $user->id
        ];
    }

}
