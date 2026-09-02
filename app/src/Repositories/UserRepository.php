<?php

namespace App\Repositories;

use App\Core\Database;
use App\Repositories\Interfaces\UserRepositoryInterface;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return $user;
    }

    public function create(string $name, string $email, string $passwordHash, string $role): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role, created_at)
            VALUES (:name, :email, :password, :role, NOW())
        ");

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function setResetToken(int $userId, string $token, string $expiresAt): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET reset_token = :token,
                reset_expires = :expires
            WHERE id = :id
        ");

        return $stmt->execute([
            'token' => $token,
            'expires' => $expiresAt,
            'id' => $userId
        ]);
    }

    public function findByResetToken(string $token): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE reset_token = :token
            LIMIT 1
        ");
        $stmt->execute([
            'token' => $token
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return $user;
    }

    public function clearResetToken(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET reset_token = NULL,
                reset_expires = NULL
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $userId
        ]);
    }

    public function updatePassword(int $userId, string $hash): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET password = :password
            WHERE id = :id
        ");

        return $stmt->execute([
            'password' => $hash,
            'id' => $userId
        ]);
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT id, name, email, role, created_at
            FROM users
            ORDER BY created_at DESC
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, name, email, role, created_at
            FROM users
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute([
            'id' => $id
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return $user;
    }

    public function updateUser(int $id, string $name, string $email, string $role): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET name = :name,
                email = :email,
                role = :role
            WHERE id = :id
        ");

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'id' => $id
        ]);
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM users
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
}