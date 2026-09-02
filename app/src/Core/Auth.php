<?php

namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function userId(): ?int
    {
        $userId = Session::get('user_id');

        if ($userId === null) {
            return null;
        }

        return (int) $userId;
    }

    public static function role(): ?string
    {
        return Session::get('role');
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    public static function isEmployee(): bool
    {
        return self::role() === 'employee';
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            Session::flash('error', 'Please log in first.');
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();

        if (!self::isAdmin()) {
            http_response_code(403);
            echo '403 Forbidden (Admin only)';
            exit;
        }
    }

    public static function login(int $userId, string $role): void
    {
        Session::regenerate();
        Session::set('user_id', $userId);
        Session::set('role', $role);
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}