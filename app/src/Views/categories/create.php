<?php

use App\Core\Csrf;
use App\Core\Session;

$error = Session::flash('error');
$success = Session::flash('success');
?>

<div class="container py-4" style="max-width: 600px;">

    <h1 class="h3 mb-3">Create Category</h1>

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
            <form method="POST" action="/categories/create">
                <?= Csrf::input() ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Category name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        maxlength="100"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="/categories" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

</div>