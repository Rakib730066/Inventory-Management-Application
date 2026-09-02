<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\View;
use App\Services\ProductService;

class ProductController extends BaseController
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
  }

    public function index(array $vars = []): void
    {
        $this->requireLogin();

        $products = $this->productService->getAllWithCategory();

        View::render('products/index', [
            'products' => $products
        ]);
    }

    public function create(array $vars = []): void
    {
        $this->requireAdmin();

        $categories = $this->productService->getAllCategories();

        View::render('products/create', [
            'categories' => $categories
        ]);
    }

    public function store(array $vars = []): void
    {
        $this->requireAdmin();
        Csrf::validateRequest();

        $result = $this->productService->create($_POST);

        if (!$result['ok']) {
            $this->redirectWithError('/products/create', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/products', 'Product created successfully.');
    }

    public function edit(array $vars = []): void
    {
        $this->requireAdmin();

        $id = (int) ($vars['id'] ?? 0);
        $product = $this->productService->find($id);
        $categories = $this->productService->getAllCategories();

        if (!$product) {
            $this->notFound('Product not found');
        }

        View::render('products/edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(array $vars = []): void
    {
        $this->requireAdmin();
        Csrf::validateRequest();

        $id = (int) ($vars['id'] ?? 0);
        $result = $this->productService->update($id, $_POST);

        if (!$result['ok']) {
            $this->redirectWithError('/products/' . $id . '/edit', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/products', 'Product updated successfully.');
    }

    public function delete(array $vars = []): void
    {
        $this->requireAdmin();
        Csrf::validateRequest();

        $id = (int) ($vars['id'] ?? 0);

        $result = $this->productService->delete($id);

        if (!$result['ok']) {
            $this->redirectWithError('/products', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/products', 'Product deleted successfully.');
    }
}