<?php
use App\Core\Auth;
use App\Core\Csrf;

$loggedIn = Auth::check();
$isAdmin  = Auth::isAdmin();
$userName = $_SESSION['name'] ?? 'User';
$userRole = $_SESSION['role'] ?? '';
$initial  = strtoupper(substr($userName, 0, 1));
?>

<nav class="navbar navbar-expand-lg" aria-label="Main navigation">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand" href="<?= $loggedIn ? '/products' : '/login' ?>">
            <span class="brand-dot">I</span>
            InvenTrack
            <span class="badge-inv ms-1">Inventory</span>
        </a>

       
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <!-- Left links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                <?php if ($loggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categories">Categories</a>
                    </li>
                    <?php if ($isAdmin): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/users">Users</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <!-- Right controls -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <?php if (!$loggedIn): ?>
                    <a class="btn btn-outline-light btn-sm" href="/login">Sign in</a>
                    <a class="btn btn-primary btn-sm" href="/register">Register</a>

                <?php else: ?>
                    <a class="btn btn-outline-warning btn-sm" href="/dashboard">Dashboard</a>

                    <!-- User pill -->
                    <div class="user-pill d-none d-md-flex">
                        <div class="user-avatar"><?= htmlspecialchars($initial) ?></div>
                        <span class="user-name"><?= htmlspecialchars((string) $userName) ?></span>
                        <span class="role-pill"><?= htmlspecialchars((string) $userRole) ?></span>
                    </div>

                   
                    <form method="POST" action="/logout" class="m-0">
                        <?= Csrf::input() ?>
                        <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>