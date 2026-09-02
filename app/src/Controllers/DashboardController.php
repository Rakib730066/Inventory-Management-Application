<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\UserService;

class DashboardController extends BaseController
{
    private ProductService $productService;
    private CategoryService $categoryService;
    private UserService $userService;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
        $this->userService = new UserService();
    }

    public function index(array $vars = []): void
    {
        $this->requireLogin();

        $stats = $this->productService->getStats();
        $categories = $this->categoryService->getAll();
        $recentProducts = $this->productService->getRecent(5);

        $users = [];

        if (Auth::isAdmin())
        {
            $users = $this->userService->getAll();
        }

        $productCount = $stats['total_products'];
        $categoryCount = count($categories);
        $userCount = count($users);
        $lowStockCount = $stats['low_stock_count'];
        $totalQuantity = $stats['total_quantity'];
        $totalInventoryValue = $stats['total_value'];

        View::render('dashboard/index',
        [
            'productCount' => $productCount,
            'categoryCount' => $categoryCount,
            'userCount' => $userCount,
            'lowStockCount' => $lowStockCount,
            'totalQuantity' => $totalQuantity,
            'totalInventoryValue' => $totalInventoryValue,
            'recentProducts' => $recentProducts
        ]);
    }
}