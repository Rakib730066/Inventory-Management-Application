<?php

use App\Core\Csrf;
use App\Core\Session;

$error = Session::flash('error');
?>

<div class="container py-4" style="max-width: 520px;">

    <h1 class="h3 mb-3">Reset password</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/reset-password">
                <?= Csrf::input() ?>

                <input
                    type="hidden"
                    name="token"
                    value="<?= htmlspecialchars((string) ($token ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                >

                <div class="mb-3">
                    <label for="password" class="form-label">New password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100">Update password</button>
            </form>
        </div>
    </div>

</div>