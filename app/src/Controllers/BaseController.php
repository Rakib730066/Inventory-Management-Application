<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;

class BaseController
{
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function redirectWithError(string $url, string $message): void
    {
        Session::flash('error', $message);
        header('Location: ' . $url);
        exit;
    }

    protected function redirectWithSuccess(string $url, string $message): void
    {
        Session::flash('success', $message);
        header('Location: ' . $url);
        exit;
    }

    protected function notFound(string $message = 'Not found'): void
    {
        http_response_code(404);
        echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        exit;
    }

    protected function forbidden(string $message = 'Forbidden'): void
    {
        http_response_code(403);
        echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        exit;
    }

    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireLogin(): void
    {
        Auth::requireLogin();
    }

    protected function requireAdmin(): void
    {
        Auth::requireAdmin();
    }
}