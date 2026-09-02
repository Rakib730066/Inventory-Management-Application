<?php

namespace App\Repositories;

use App\Core\Database;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use PDO;

class CategoryRepository implements CategoryRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT id, name
            FROM categories
            ORDER BY name ASC
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, name
            FROM categories
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute([
            'id' => $id
        ]);

        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            return null;
        }

        return $category;
    }

    public function create(string $name): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name, created_at)
            VALUES (:name, NOW())
        ");
        $stmt->execute([
            'name' => $name
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name): bool
    {
        $stmt = $this->db->prepare("
            UPDATE categories
            SET name = :name
            WHERE id = :id
        ");

        return $stmt->execute([
            'name' => $name,
            'id' => $id
        ]);
    }
    public function hasProducts(int $id): bool
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*) 
        FROM products
        WHERE category_id = :id
    ");
    $stmt->execute([
        'id' => $id
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM categories
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
    public function findByName(string $name): ?array
{
    $stmt = $this->db->prepare("
        SELECT id, name
        FROM categories
        WHERE name = :name
        LIMIT 1
    ");
    $stmt->execute([
        'name' => $name
    ]);

    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    return $category ?: null;
}
}