<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?array;

    public function create(string $name, string $email, string $passwordHash, string $role): int;

    public function setResetToken(int $userId, string $token, string $expiresAt): bool;

    public function findByResetToken(string $token): ?array;

    public function clearResetToken(int $userId): bool;

    public function updatePassword(int $userId, string $hash): bool;

    public function getAll(): array;

    public function find(int $id): ?array;

    public function updateUser(int $id, string $name, string $email, string $role): bool;

    public function deleteUser(int $id): bool;
}