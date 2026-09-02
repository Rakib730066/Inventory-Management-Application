<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;
use App\Core\Validator;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;

class ProductService
{
    private ProductRepository $products;
    private CategoryRepository $categories;

    public function __construct()
    {
        $this->products = new ProductRepository();
        $this->categories = new CategoryRepository();
    }

    public function getAllWithCategory(): array
    {
        return $this->products->getAllWithCategory();
    }

    public function find(int $id): ?array
    {
        return $this->products->find($id);
    }

    public function getAllCategories(): array
    {
        return $this->categories->getAll();
    }

    public function getStats(): array
    {
        return $this->products->getStats(AppConfig::LOW_STOCK_THRESHOLD);
    }

    public function getRecent(int $limit = 5): array
    {
        return $this->products->getRecent($limit);
    }

    public function getLowStock(): array
    {
        return $this->products->getLowStock(AppConfig::LOW_STOCK_THRESHOLD);
    }

    public function create(array $input): array
    {
        $data = $this->sanitizeProductData($input);
        $errors = $this->validateProductData($data);

        if (!$this->categories->find($data['category_id'])) {
            $errors[] = 'Selected category does not exist.';
        }

        if (!empty($errors)) {
            return [
                'ok' => false,
                'errors' => $errors,
                'data' => $data
            ];
        }

        $id = $this->products->create($data);

        if ($id > 0) {
            return [
                'ok' => true,
                'id' => $id,
                'product' => [
                    'id' => $id,
                    'name' => $data['name'],
                    'category_id' => $data['category_id'],
                    'price' => $data['price'],
                    'quantity' => $data['quantity'],
                    'description' => $data['description'],
                    'sku' => $data['sku']
                ]
            ];
        }

        return [
            'ok' => false,
            'errors' => ['Failed to create product.'],
            'data' => $data
        ];
    }

    public function update(int $id, array $input): array
    {
        if ($id <= 0) {
            return [
                'ok' => false,
                'errors' => ['Invalid product id.']
            ];
        }

        if (!$this->products->find($id)) {
            return [
                'ok' => false,
                'errors' => ['Product not found.']
            ];
        }

        $data = $this->sanitizeProductData($input);
        $errors = $this->validateProductData($data);

        if (!$this->categories->find($data['category_id'])) {
            $errors[] = 'Selected category does not exist.';
        }

        if (!empty($errors)) {
            return [
                'ok' => false,
                'errors' => $errors,
                'data' => $data
            ];
        }

        $ok = $this->products->update($id, $data);

        if ($ok) {
            return [
                'ok' => true,
                'errors' => []
            ];
        }

        return [
            'ok' => false,
            'errors' => ['Failed to update product.']
        ];
    }

    public function delete(int $id): array
    {
        if ($id <= 0) {
            return [
                'ok' => false,
                'errors' => ['Invalid product id.']
            ];
        }

        $ok = $this->products->delete($id);

        if ($ok) {
            return [
                'ok' => true,
                'errors' => []
            ];
        }

        return [
            'ok' => false,
            'errors' => ['Failed to delete product.']
        ];
    }

    public function updateQuantity(int $id, int $quantity): array
    {
        if ($id <= 0) {
            return [
                'ok' => false,
                'errors' => ['Invalid product id.']
            ];
        }

        if ($quantity < 0) {
            return [
                'ok' => false,
                'errors' => ['Quantity cannot be negative.']
            ];
        }

        if (!$this->products->find($id)) {
            return [
                'ok' => false,
                'errors' => ['Product not found.']
            ];
        }

        $ok = $this->products->updateQuantity($id, $quantity);

        return [
            'ok' => $ok,
            'errors' => $ok ? [] : ['Failed to update quantity.'],
            'low_stock' => $quantity <= AppConfig::LOW_STOCK_THRESHOLD
        ];
    }

    private function sanitizeProductData(array $input): array
    {
        $data = [];
        $data['name'] = Validator::sanitizeString($input['name'] ?? '');
        $data['category_id'] = (int) ($input['category_id'] ?? 0);
        $data['price'] = (float) ($input['price'] ?? 0);
        $data['quantity'] = (int) ($input['quantity'] ?? 0);
        $data['description'] = Validator::sanitizeString($input['description'] ?? '');
        $data['sku'] = strtoupper(Validator::sanitizeString($input['sku'] ?? ''));

        return $data;
    }

    private function validateProductData(array $data): array
    {
        $errors = [];

        if (!Validator::required($data['name'])) {
            $errors[] = 'Product name is required.';
        } elseif (!Validator::maxLength($data['name'], 100)) {
            $errors[] = 'Product name must be at most 100 characters.';
        }

        if ($data['category_id'] <= 0) {
            $errors[] = 'Category is required.';
        }

        if (!Validator::price($data['price'])) {
            $errors[] = 'Price must be a valid non-negative number.';
        }

        if (!Validator::nonNegativeInteger($data['quantity'])) {
            $errors[] = 'Quantity must be a non-negative integer.';
        }

        if ($data['description'] !== '' && !Validator::maxLength($data['description'], 1000)) {
            $errors[] = 'Description must be at most 1000 characters.';
        }

        if ($data['sku'] !== '' && !preg_match('/^[A-Z0-9\-_]+$/', $data['sku'])) {
            $errors[] = 'SKU may only contain letters, numbers, hyphens, and underscores.';
        }

        return $errors;
    }
}