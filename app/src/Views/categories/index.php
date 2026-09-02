<?php

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;

$error = Session::flash('error');
$success = Session::flash('success');
$isAdmin = Auth::isAdmin();
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 m-0">Categories</h1>

        <?php if ($isAdmin): ?>
            <a href="/categories/create" class="btn btn-primary">Add Category</a>
        <?php endif; ?>
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
        <div class="list-group list-group-flush">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $c): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?= htmlspecialchars((string) $c['name'], ENT_QUOTES, 'UTF-8') ?>
                        </span>

                        <?php if ($isAdmin): ?>
                            <div>
                                <a href="/categories/<?= (int) $c['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="/categories/<?= (int) $c['id'] ?>/delete"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this category?');">
                                    <?= Csrf::input() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-center">
                    No categories yet.
                    <?php if ($isAdmin): ?>
                        <a href="/categories/create">Create the first one</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>