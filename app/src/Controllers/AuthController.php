<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\View;
use App\Services\AuthService;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function showLogin(array $vars = []): void
    {
        View::render('auth/login');
    }

    public function login(array $vars = []): void
    {
        Csrf::validateRequest();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->authService->login($email, $password);

        if (!$result['ok']) {
            $this->redirectWithError('/login', implode(' ', $result['errors']));
        }

        Session::set('name', $result['user']['name'] ?? '');
        $this->redirectWithSuccess('/products', 'Welcome back, ' . ($result['user']['name'] ?? '') . '!');
    }

    public function logout(array $vars = []): void
    {
        Csrf::validateRequest();
        Auth::logout();
        $this->redirect('/login');
    }

    public function showRegister(array $vars = []): void
    {
        View::render('auth/register');
    }

    public function register(array $vars = []): void
    {
        Csrf::validateRequest();

        $result = $this->authService->register($_POST);

        if (!$result['ok']) {
            $this->redirectWithError('/register', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/login', 'Account created. Please log in.');
    }

    public function showForgotPassword(array $vars = []): void
    {
        View::render('auth/forgot_password');
    }

    public function sendResetLink(array $vars = []): void
    {
        Csrf::validateRequest();

        $email = $_POST['email'] ?? '';
        $result = $this->authService->generateResetLink($email);

        Session::flash('success', $result['message']);

        if (!empty($result['reset_link'])) {
            Session::set('reset_link', $result['reset_link']);
        } else {
            Session::remove('reset_link');
        }

        $this->redirect('/forgot-password');
    }

    public function showResetPassword(array $vars = []): void
    {
        $token = trim($_GET['token'] ?? '');
        $result = $this->authService->validateResetToken($token);

        if (!$result['ok']) {
            $this->redirectWithError('/forgot-password', implode(' ', $result['errors']));
        }

        View::render('auth/reset_password', ['token' => $token]);
    }

    public function resetPassword(array $vars = []): void
    {
        Csrf::validateRequest();

        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';

        $result = $this->authService->resetPassword($token, $password);

        if (!$result['ok']) {
            Session::flash('error', implode(' ', $result['errors']));
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        Session::remove('reset_link');
        $this->redirectWithSuccess('/login', 'Password updated. Please log in.');
    }
}