<?php

use App\Core\Csrf;
use App\Core\Session;

$error = Session::flash('error');
$success = Session::flash('success');
?>

<div class="container py-4" style="max-width: 780px;">

    <h1 class="h3 mb-3">Add Product</h1>

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
            <form method="POST" action="/products/create">
                <?= Csrf::input() ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Product name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="sku" class="form-label">SKU</label>
                    <input
                        type="text"
                        class="form-control"
                        id="sku"
                        name="sku"
                    >
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select category</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= (int) $c['id'] ?>">
                                <?= htmlspecialchars((string) $c['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        class="form-control"
                        id="price"
                        name="price"
                        value="0.00"
                    >
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input
                        type="number"
                        min="0"
                        class="form-control"
                        id="quantity"
                        name="quantity"
                        value="0"
                    >
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="3"
                        maxlength="1000"
                    ></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save product</button>
                <a href="/products" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

</div>