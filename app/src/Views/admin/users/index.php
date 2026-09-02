<?php

use App\Core\Csrf;
use App\Core\Session;

$error = Session::flash('error');
$success = Session::flash('success');
$myId = (int) ($_SESSION['user_id'] ?? 0);
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 m-0">User Management</h1>
        <a href="/register" class="btn btn-primary">Add User</a>
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
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $u): ?>
                                <?php $isSelf = (int) $u['id'] === $myId; ?>
                                <tr>
                                    <td><?= (int) $u['id'] ?></td>
                                    <td>
                                        <?= htmlspecialchars((string) $u['name'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php if ($isSelf): ?>
                                            <span class="badge bg-warning text-dark">You</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars((string) $u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) $u['role'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($u['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <a href="/admin/users/<?= (int) $u['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>

                                        <?php if ($isSelf): ?>
                                            <button class="btn btn-sm btn-outline-danger" disabled>
                                                Delete
                                            </button>
                                        <?php else: ?>
                                            <form method="POST"
                                                  action="/admin/users/<?= (int) $u['id'] ?>/delete"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Delete this user?');">
                                                <?= Csrf::input() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>