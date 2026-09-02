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
        <h1 class="h3 m-0">Products</h1>

        <?php if ($isAdmin): ?>
            <a href="/products/create" class="btn btn-primary">Add Product</a>
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

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" id="search" class="form-control">
                </div>

                <div class="col-md-4">
                    <label for="categoryFilter" class="form-label">Category</label>
                    <select id="categoryFilter" class="form-select">
                        <option value="">All categories</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="lowStockOnly">
                        <label class="form-check-label" for="lowStockOnly">
                            Low stock only
                        </label>
                    </div>
                </div>
            </div>

            <div id="status" class="mt-2"></div>
        </div>
    </div>

    <div id="productsWrap" class="row g-3">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <?php $qty = (int) ($p['quantity'] ?? 0); ?>

                <div class="col-md-6 col-lg-4 product-card"
                     data-name="<?= htmlspecialchars(strtolower((string) $p['name']), ENT_QUOTES, 'UTF-8') ?>"
                     data-sku="<?= htmlspecialchars(strtolower((string) ($p['sku'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"
                     data-cat="<?= htmlspecialchars(strtolower((string) ($p['category_name'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">

                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="h5"><?= htmlspecialchars((string) $p['name'], ENT_QUOTES, 'UTF-8') ?></h2>

                            <p class="mb-1">
                                <strong>Category:</strong>
                                <?= htmlspecialchars((string) ($p['category_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <?php if (!empty($p['sku'])): ?>
                                <p class="mb-1">
                                    <strong>SKU:</strong>
                                    <?= htmlspecialchars((string) $p['sku'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($p['description'])): ?>
                                <p class="mb-2">
                                    <?= htmlspecialchars((string) $p['description'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>

                            <p class="mb-2">
                                <strong>Price:</strong>
                                &euro;<?= number_format((float) ($p['price'] ?? 0), 2) ?>
                            </p>

                            <div class="mb-2">
                                <label for="qty-<?= (int) $p['id'] ?>" class="form-label">Quantity</label>
                                <input
                                    type="number"
                                    min="0"
                                    class="form-control qty-input"
                                    id="qty-<?= (int) $p['id'] ?>"
                                    value="<?= $qty ?>"
                                    data-id="<?= (int) $p['id'] ?>"
                                >
                            </div>

                            <p class="mb-0">
                                <strong>Status:</strong>
                                <?php if ($qty === 0): ?>
                                    Out of stock
                                <?php elseif ($qty <= 5): ?>
                                    Low stock
                                <?php else: ?>
                                    In stock
                                <?php endif; ?>
                            </p>
                        </div>

                        <?php if ($isAdmin): ?>
                            <div class="card-footer">
                                <a href="/products/<?= (int) $p['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="/products/<?= (int) $p['id'] ?>/delete"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this product?');">
                                    <?= Csrf::input() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    No products found.
                    <?php if ($isAdmin): ?>
                        <a href="/products/create">Add the first product</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="/assets/js/products.js"></script>