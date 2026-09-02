<?php

use App\Core\Auth;
use App\Core\Session;

$error = Session::flash('error');
$success = Session::flash('success');
$isAdmin = Auth::isAdmin();
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 m-0">Dashboard</h1>

        <div>
            <a href="/products" class="btn btn-primary btn-sm">View Products</a>

            <?php if ($isAdmin): ?>
                <a href="/products/create" class="btn btn-secondary btn-sm">Add Product</a>
                <a href="/categories/create" class="btn btn-secondary btn-sm">Add Category</a>
            <?php endif; ?>
        </div>
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

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="h6">Total Products</h2>
                    <p class="dashboard-stat-number mb-0"><?= (int) $productCount ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="h6">Categories</h2>
                    <p class="dashboard-stat-number mb-0"><?= (int) $categoryCount ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="h6">Low Stock</h2>
                    <p class="dashboard-stat-number mb-0"><?= (int) $lowStockCount ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="h6">Total Units</h2>
                    <p class="dashboard-stat-number mb-0"><?= (int) $totalQuantity ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="h5 mb-3">Recent Products</h2>

                    <?php if (empty($recentProducts)): ?>
                        <p>No products yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentProducts as $p): ?>
                                        <?php $qty = (int) ($p['quantity'] ?? 0); ?>
                                        <tr>
                                            <td><?= htmlspecialchars((string) $p['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars((string) ($p['category_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>&euro;<?= number_format((float) ($p['price'] ?? 0), 2) ?></td>
                                            <td><?= $qty ?></td>
                                            <td>
                                                <?php if ($qty === 0): ?>
                                                    Out of stock
                                                <?php elseif ($qty <= 5): ?>
                                                    Low stock
                                                <?php else: ?>
                                                    In stock
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="h5 mb-3">Summary</h2>

                    <p><strong>Inventory Value:</strong> &euro;<?= number_format((float) $totalInventoryValue, 2) ?></p>

                    <?php if ($isAdmin): ?>
                        <p><strong>Registered Users:</strong> <?= (int) $userCount ?></p>
                    <?php endif; ?>

                    <?php if ((int) $lowStockCount > 0): ?>
                        <div class="dashboard-alert dashboard-alert-warning">
                            <strong>Low Stock Alert:</strong>
                            <?= (int) $lowStockCount ?> item(s) need restocking.
                        </div>

                    <?php else: ?>
                        <div class="dashboard-alert dashboard-alert-success">
                            <strong>Stock Status:</strong>
                            All stock levels are healthy.
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-2">
                        <a href="/products" class="btn btn-primary btn-sm">Manage Products</a>
                        <a href="/categories" class="btn btn-secondary btn-sm">Manage Categories</a>

                        <?php if ($isAdmin): ?>
                            <a href="/admin/users" class="btn btn-secondary btn-sm">Manage Users</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="low-stock-widget">
        <div class="card-body">
            <h2 class="h5 mb-3">Live Low-Stock Alert</h2>

            <div class="widget-spinner mb-3">
                Loading stock data...
            </div>

            <div class="widget-empty mb-3" style="display:none;">
                All products are well stocked.
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th>Qty</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="/assets/js/low_stock_widget.js"></script>