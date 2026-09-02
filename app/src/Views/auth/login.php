<?php

use App\Core\Csrf;
use App\Core\Session;

$error = Session::flash('error');
$success = Session::flash('success');
?>

<div class="container py-5" style="max-width: 500px;">

    <h1 class="h3 mb-4 text-center">Login</h1>

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
            <form method="POST" action="/login">
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

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <div class="mt-3 text-center">
                <a href="/register">Create account</a>
                <span class="mx-2">|</span>
                <a href="/forgot-password">Forgot password?</a>
            </div>
        </div>
    </div>

    <div class="mt-3 text-center small">
        <div>Admin: admin@test.com / admin123</div>
        <div>Employee: employee@test.com / emp123</div>
    </div>

</div>