<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use PDO;

class ProductRepository implements ProductRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllWithCategory(): array
    {
        $sql = "
            SELECT p.id, p.name, p.price, p.quantity, p.description,
                   p.sku, p.created_at, p.category_id,
                   c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            ORDER BY p.created_at DESC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, name, category_id, price, quantity, description, sku, created_at
            FROM products
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute([
            'id' => $id
        ]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return null;
        }

        return $product;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO products (name, category_id, price, quantity, description, sku, created_at)
            VALUES (:name, :category_id, :price, :quantity, :description, :sku, NOW())
        ");

        $stmt->execute([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'description' => $data['description'],
            'sku' => $data['sku']
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE products
            SET name = :name,
                category_id = :category_id,
                price = :price,
                quantity = :quantity,
                description = :description,
                sku = :sku
            WHERE id = :id
        ");

        return $stmt->execute([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'description' => $data['description'],
            'sku' => $data['sku'],
            'id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM products
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function updateQuantity(int $id, int $quantity): bool
    {
        $stmt = $this->db->prepare("
            UPDATE products
            SET quantity = :quantity
            WHERE id = :id
        ");

        return $stmt->execute([
            'quantity' => $quantity,
            'id' => $id
        ]);
    }

    public function getStats(int $lowStockThreshold): array
    {
        $stmt = $this->db->prepare("
            SELECT
                COUNT(*) AS total_products,
                COALESCE(SUM(quantity), 0) AS total_quantity,
                COALESCE(SUM(price * quantity), 0) AS total_value,
                SUM(quantity <= :threshold) AS low_stock_count
            FROM products
        ");
        $stmt->execute([
            'threshold' => $lowStockThreshold
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_products' => (int) ($row['total_products'] ?? 0),
            'total_quantity' => (int) ($row['total_quantity'] ?? 0),
            'total_value' => (float) ($row['total_value'] ?? 0),
            'low_stock_count' => (int) ($row['low_stock_count'] ?? 0)
        ];
    }

    public function getRecent(int $limit): array
    {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.price, p.quantity, p.sku, p.created_at,
                   c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            ORDER BY p.created_at DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getLowStock(int $threshold): array
{
    $stmt = $this->db->prepare("
        SELECT p.id, p.name, p.price, p.quantity, p.sku, p.created_at,
               c.name AS category_name
        FROM products p
        JOIN categories c ON c.id = p.category_id
        WHERE p.quantity <= :threshold
        ORDER BY p.quantity ASC, p.created_at DESC
    ");
    $stmt->execute([
        'threshold' => $threshold
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}