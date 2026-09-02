<?php

namespace App\Services;

use App\Core\Auth;
use App\Core\Validator;
use App\Repositories\UserRepository;

class AuthService
{
    private UserRepository $users;

    public function __construct()
    {

        $this->users = new UserRepository();
    }

    public function login(string $email, string $password): array
    {
        $email = Validator::sanitizeString($email);

        if (!Validator::email($email) || !Validator::required($password)) {
            return [
                'ok' => false,
                'errors' => ['Invalid email or password.']
            ];
        }

        $user = $this->users->findByEmail($email);

        if (!$user) {
            return [
                'ok' => false,
                'errors' => ['Invalid email or password.']
            ];
        }

        if (!password_verify($password, $user['password'])) {
            return [
                'ok' => false,
                'errors' => ['Invalid email or password.']
            ];
        }

        Auth::login((int) $user['id'], (string) $user['role']);

        return [
            'ok' => true,
            'user' => $user
        ];
    }

    public function register(array $input): array
    {
        $name = Validator::sanitizeString($input['name'] ?? '');
        $email = Validator::sanitizeString($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $role = 'employee';


        $errors = [];

        if (!Validator::required($name)) {
            $errors[] = 'Name is required.';
        } elseif (!Validator::maxLength($name, 100)) {
            $errors[] = 'Name must be at most 100 characters.';
        }

        if (!Validator::email($email)) {
            $errors[] = 'A valid email address is required.';
        }

        if (!Validator::minLength($password, 6)) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($this->users->findByEmail($email)) {
            $errors[] = 'Email already exists.';
        }

        if (!empty($errors)) {
            return [
                'ok' => false,
                'errors' => $errors
            ];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $id = $this->users->create($name, $email, $hash, $role);

        if ($id > 0) {
            return [
                'ok' => true,
                'id' => $id,
                'errors' => []
            ];
        }

        return [
            'ok' => false,
            'id' => 0,
            'errors' => ['Failed to create account.']
        ];
    }

    public function generateResetLink(string $email): array
    {
        $email = Validator::sanitizeString($email);

        $result = [
            'ok' => true,
            'message' => 'If that email exists, a reset link has been generated.',
            'reset_link' => null
        ];

        if (!Validator::email($email)) {
            return $result;
        }

        $user = $this->users->findByEmail($email);

        if (!$user) {
            return $result;
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 1800);

        $this->users->setResetToken((int) $user['id'], $token, $expiresAt);

        $result['reset_link'] = '/reset-password?token=' . urlencode($token);

        return $result;
    }

    public function validateResetToken(string $token): array
    {
        $token = Validator::sanitizeString($token);

        if ($token === '') {
            return [
                'ok' => false,
                'errors' => ['Invalid reset token.']
            ];
        }

        $user = $this->users->findByResetToken($token);

        if (!$user) {
            return [
                'ok' => false,
                'errors' => ['Reset link is invalid or expired.']
            ];
        }

        $expires = $user['reset_expires'] ?? null;

        if (!$expires || strtotime((string) $expires) < time()) {
            $this->users->clearResetToken((int) $user['id']);

            return [
                'ok' => false,
                'errors' => ['Reset link is expired.']
            ];
        }

        return [
            'ok' => true,
            'user' => $user
        ];
    }

    public function resetPassword(string $token, string $password): array
    {
        if (!Validator::minLength($password, 6)) {
            return [
                'ok' => false,
                'errors' => ['Password must be at least 6 characters.']
            ];
        }

        $validation = $this->validateResetToken($token);

        if (!$validation['ok']) {
            return $validation;
        }

        $user = $validation['user'];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->users->updatePassword((int) $user['id'], $hash);
        $this->users->clearResetToken((int) $user['id']);

        return [
            'ok' => true
        ];
    }
}