<?php

// CSRF protection - verify token matches session to prevent cross-site attacks
// Attacker can't submit forms from their site because they can't guess the token
namespace App\Core;

class Csrf
{
    public static function token(): string
    {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }

        return Session::get('_csrf_token');
    }

    public static function input(): string
    {
        $token = self::token();

        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify($token): bool
    {
        $sessionToken = Session::get('_csrf_token');

        if (!$sessionToken || !$token) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function validateRequest(): void
    {
        $token = $_POST['_csrf_token'] ?? '';

        if (!self::verify($token)) {
            http_response_code(419);
            exit('Invalid CSRF token.');
        }
    }
}