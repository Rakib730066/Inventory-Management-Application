<?php

use App\Core\Csrf;
use App\Core\Session;

$error = Session::flash('error');
$success = Session::flash('success');
$resetLink = $_SESSION['reset_link'] ?? null;
?>

<div class="container py-4" style="max-width: 520px;">

    <h1 class="h3 mb-3">Forgot password</h1>

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
            <form method="POST" action="/forgot-password">
                <?= Csrf::input() ?>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">Generate reset link</button>
            </form>
        </div>
    </div>

    <?php if ($resetLink): ?>
        <div class="alert alert-info mt-3">
            <strong>Demo reset link:</strong><br>
            <a href="<?= htmlspecialchars((string) $resetLink, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars((string) $resetLink, ENT_QUOTES, 'UTF-8') ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="mt-3">
        <a href="/login">&larr; Back to login</a>
    </div>

</div>