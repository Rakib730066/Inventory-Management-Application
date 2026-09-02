<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Services\UserService;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index(array $vars = []): void
    {
        $this->requireAdmin();

        $users = $this->userService->getAll();

        View::render('admin/users/index', [
            'users' => $users
        ]);
    }

    public function edit(array $vars = []): void
    {
        $this->requireAdmin();

        $id = (int) ($vars['id'] ?? 0);
        $user = $this->userService->find($id);

        if (!$user) {
            $this->notFound('User not found');
        }

        View::render('admin/users/edit', [
            'user' => $user
        ]);
    }

    public function update(array $vars = []): void
    {
        $this->requireAdmin();
        Csrf::validateRequest();

        $id = (int) ($vars['id'] ?? 0);
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'employee';

        $result = $this->userService->update($id, $name, $email, $role);

        if (!$result['ok']) {
            $this->redirectWithError('/admin/users/' . $id . '/edit', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/admin/users', 'User updated successfully.');
    }

    public function delete(array $vars = []): void
    {
        $this->requireAdmin();
        Csrf::validateRequest();

        $id = (int) ($vars['id'] ?? 0);
        $result = $this->userService->delete($id);

        if (!$result['ok']) {
            $this->redirectWithError('/admin/users', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/admin/users', 'User deleted successfully.');
    }
}