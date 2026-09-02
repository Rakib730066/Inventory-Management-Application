<?php

namespace App\Services;

use App\Core\Validator;
use App\Repositories\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categories;

    public function __construct()
    {
        $this->categories = new CategoryRepository();
    }

    public function getAll(): array
    {
        return $this->categories->getAll();
    }

    public function find(int $id): ?array
    {
        return $this->categories->find($id);
    }

public function create(string $name): array
{
    $name = Validator::sanitizeString($name);
    $errors = $this->validateName($name);

    if (!empty($errors)) {
        return [
            'ok' => false,
            'errors' => $errors
        ];
    }

    $existing = $this->categories->findByName($name);

    if ($existing) {
        return [
            'ok' => false,
            'errors' => ['Category name already exists.']
        ];
    }

    try {
        $id = $this->categories->create($name);

        if ($id > 0) {
            return [
                'ok' => true,
                'id' => $id,
                'errors' => []
            ];
        }
    } catch (\PDOException $e) {
        return [
            'ok' => false,
            'id' => 0,
            'errors' => ['Failed to create category.']
        ];
    }

    return [
        'ok' => false,
        'id' => 0,
        'errors' => ['Failed to create category.']
    ];
}

   public function update(int $id, string $name): array
{
    if ($id <= 0) {
        return [
            'ok' => false,
            'errors' => ['Invalid category id.']
        ];
    }

    $existing = $this->categories->find($id);

    if (!$existing) {
        return [
            'ok' => false,
            'errors' => ['Category not found.']
        ];
    }

    $name = Validator::sanitizeString($name);
    $errors = $this->validateName($name);

    if (!empty($errors)) {
        return [
            'ok' => false,
            'errors' => $errors
        ];
    }

    $duplicate = $this->categories->findByName($name);

    if ($duplicate && (int) $duplicate['id'] !== $id) {
        return [
            'ok' => false,
            'errors' => ['Category name already exists.']
        ];
    }

    try {
        $ok = $this->categories->update($id, $name);

        if ($ok) {
            return [
                'ok' => true,
                'errors' => []
            ];
        }
    } catch (\PDOException $e) {
        return [
            'ok' => false,
            'errors' => ['Failed to update category.']
        ];
    }

    return [
        'ok' => false,
        'errors' => ['Failed to update category.']
    ];
}

public function delete(int $id): array
{
    if ($id <= 0) {
        return [
            'ok' => false,
            'errors' => ['Invalid category id.']
        ];
    }

    $existing = $this->categories->find($id);

    if (!$existing) {
        return [
            'ok' => false,
            'errors' => ['Category not found.']
        ];
    }

    if ($this->categories->hasProducts($id)) {
        return [
            'ok' => false,
            'errors' => ['You cannot delete this category because products are still assigned to it.']
        ];
    }

    $ok = $this->categories->delete($id);

    if ($ok) {
        return [
            'ok' => true,
            'errors' => []
        ];
    }

    return [
        'ok' => false,
        'errors' => ['Failed to delete category.']
    ];
}

    private function validateName(string $name): array
    {
        $errors = [];

        if (!Validator::required($name)) {
            $errors[] = 'Category name is required.';
        } elseif (!Validator::maxLength($name, 100)) {
            $errors[] = 'Category name must be at most 100 characters.';
        }

        return $errors;
    }
}