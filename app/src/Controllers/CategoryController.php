<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\View;
use App\Services\CategoryService;

class CategoryController extends BaseController
{
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function index(array $vars = []): void
    {
        $this->requireLogin();

        $categories = $this->categoryService->getAll();

        View::render('categories/index', [
            'categories' => $categories
        ]);
    }

    public function create(array $vars = []): void
    {
        $this->requireAdmin();

        View::render('categories/create');
    }

    public function store(array $vars = []): void
    {
        $this->requireAdmin();
        Csrf::validateRequest();

        $name = $_POST['name'] ?? '';
        $result = $this->categoryService->create($name);

        if (!$result['ok']) {
            $this->redirectWithError('/categories/create', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/categories', 'Category created successfully.');
    }

    public function edit(array $vars = []): void
    {
        $this->requireAdmin();

        $id = (int) ($vars['id'] ?? 0);
        $category = $this->categoryService->find($id);

        if (!$category) {
            $this->notFound('Category not found');
        }

        View::render('categories/edit', [
            'category' => $category
        ]);
    }

    public function update(array $vars = []): void
    {
        $this->requireAdmin();
        Csrf::validateRequest();

        $id = (int) ($vars['id'] ?? 0);
        $name = $_POST['name'] ?? '';

        $result = $this->categoryService->update($id, $name);

        if (!$result['ok']) {
            $this->redirectWithError('/categories/' . $id . '/edit', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/categories', 'Category updated successfully.');
    }

    public function delete(array $vars = []): void
    {
        $this->requireAdmin();
        Csrf::validateRequest();

        $id = (int) ($vars['id'] ?? 0);
        $result = $this->categoryService->delete($id);

        if (!$result['ok']) {
            $this->redirectWithError('/categories', implode(' ', $result['errors']));
        }

        $this->redirectWithSuccess('/categories', 'Category deleted successfully.');
    }
}