<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function getAllWithCategory(): array;

    public function find(int $id): ?array;

    public function create(array $data): int;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function updateQuantity(int $id, int $quantity): bool;
    
    public function getLowStock(int $threshold): array;

}