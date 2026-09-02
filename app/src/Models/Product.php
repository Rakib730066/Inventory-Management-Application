<?php

namespace App\Models;

class Product
{
    public ?int $id;
    public string $name;
    public int $categoryId;
    public float $price;
    public int $quantity;
    public string $description;
    public string $sku;
    public ?string $categoryName;
    public ?string $createdAt;

    public function __construct()
    {
        $this->id = null;
        $this->name = '';
        $this->categoryId = 0;
        $this->price = 0.0;
        $this->quantity = 0;
        $this->description = '';
        $this->sku = '';
        $this->categoryName = null;
        $this->createdAt = null;
    }
}