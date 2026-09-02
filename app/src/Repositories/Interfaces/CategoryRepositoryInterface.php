<?php

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    public function getAll(): array;

    public function find(int $id): ?array;

    public function create(string $name): int;

    public function update(int $id, string $name): bool;

    public function delete(int $id): bool;
    
    public function hasProducts(int $id): bool;

    public function findByName(string $name): ?array;
}