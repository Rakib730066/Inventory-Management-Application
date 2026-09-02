<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProductService;

class ApiController extends BaseController
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function products(array $vars = []): void
    {
        $this->requireLogin();

        $products = $this->productService->getAllWithCategory();
        $this->json($products);
    }

    public function lowStock(array $vars = []): void
    {
        $this->requireLogin();

        $products = $this->productService->getLowStock();
        $this->json($products);
    }

    public function updateQuantity(array $vars = []): void
    {
        $this->requireLogin();

        $id = (int) ($vars['id'] ?? 0);

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!is_array($data)) {
            $data = [];
        }

        $quantity = (int) ($data['quantity'] ?? -1);

        $result = $this->productService->updateQuantity($id, $quantity);

        if (!$result['ok']) {
            $this->json($result, 400);
            return;
        }

        $this->json($result);
    }
}