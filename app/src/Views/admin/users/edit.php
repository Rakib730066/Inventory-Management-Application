<?php

use App\Core\Csrf;
use App\Core\Session;

$error = Session::flash('error');
$success = Session::flash('success');
?>

<div class="container py-4" style="max-width: 720px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 m-0">Edit User</h1>
        <a href="/admin/users" class="btn btn-outline-secondary btn-sm">&larr; Back</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/admin/users/<?= (int) $user['id'] ?>/edit">
                <?= Csrf::input() ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Full name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        maxlength="100"
                        required
                        value="<?= htmlspecialchars((string) $user['name'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        required
                        value="<?= htmlspecialchars((string) $user['email'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="employee" <?= $user['role'] === 'employee' ? 'selected' : '' ?>>Employee</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Created at</label>
                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars((string) ($user['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        disabled
                    >
                </div>

                <button type="submit" class="btn btn-primary">Save changes</button>
                <a href="/admin/users" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>