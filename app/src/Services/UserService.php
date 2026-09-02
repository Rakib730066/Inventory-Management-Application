<?php

namespace App\Services;

use App\Core\Auth;
use App\Core\Validator;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function getAll(): array
    {
        return $this->users->getAll();
    }

    public function find(int $id): ?array
    {
        return $this->users->find($id);
    }

    public function update(int $id, string $name, string $email, string $role): array
    {
        if ($id <= 0) {
            return [
                'ok' => false,
                'errors' => ['Invalid user id.']
            ];
        }

        $existing = $this->users->find($id);

        if (!$existing) {
            return [
                'ok' => false,
                'errors' => ['User not found.']
            ];
        }

        $name = Validator::sanitizeString($name);
        $email = Validator::sanitizeString($email);

        if ($role === 'admin') {
            $role = 'admin';
        } else {
            $role = 'employee';
        }

        $errors = [];

        if (!Validator::required($name)) {
            $errors[] = 'Name is required.';
        } elseif (!Validator::maxLength($name, 100)) {
            $errors[] = 'Name must be at most 100 characters.';
        }

        if (!Validator::email($email)) {
            $errors[] = 'A valid email address is required.';
        }

        $byEmail = $this->users->findByEmail($email);

        if ($byEmail && (int) $byEmail['id'] !== $id) {
            $errors[] = 'That email is already in use.';
        }

        if ($id === Auth::userId() && $role !== 'admin') {
            $errors[] = 'You cannot change your own role to employee.';
        }

        if (!empty($errors)) {
            return [
                'ok' => false,
                'errors' => $errors
            ];
        }

        $ok = $this->users->updateUser($id, $name, $email, $role);

        if ($ok) {
            return [
                'ok' => true,
                'errors' => []
            ];
        }

        return [
            'ok' => false,
            'errors' => ['Failed to update user.']
        ];
    }

    public function delete(int $id): array
    {
        if ($id <= 0) {
            return [
                'ok' => false,
                'errors' => ['Invalid user id.']
            ];
        }

        if ($id === Auth::userId()) {
            return [
                'ok' => false,
                'errors' => ['You cannot delete your own account.']
            ];
        }

        $existing = $this->users->find($id);

        if (!$existing) {
            return [
                'ok' => false,
                'errors' => ['User not found.']
            ];
        }

        $ok = $this->users->deleteUser($id);

        if ($ok) {
            return [
                'ok' => true,
                'errors' => []
            ];
        }

        return [
            'ok' => false,
            'errors' => ['Failed to delete user.']
        ];
    }
}