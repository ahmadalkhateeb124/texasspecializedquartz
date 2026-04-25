<?php

/**
 * InventorySlabRepository — CRUD for the inventory_slabs table.
 * Distinct from the `products` table (which represents remnants).
 */
class InventorySlabRepository
{
    public const MATERIAL_TYPES = ['granite', 'marble', 'quartzite', 'quartz', 'other'];

    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        return $this->pdo->query("
            SELECT * FROM inventory_slabs
            ORDER BY material_type ASC, sort_order ASC, id ASC
        ")->fetchAll();
    }

    public function allActive(): array
    {
        return $this->pdo->query("
            SELECT * FROM inventory_slabs
            WHERE status = 'active'
            ORDER BY sort_order ASC, id ASC
        ")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM inventory_slabs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO inventory_slabs (name, material_type, size, quantity, image, sort_order, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $d['name'],
            $d['material_type'] ?? 'granite',
            $d['size']          ?? '3 CM',
            (int)($d['quantity'] ?? 0),
            $d['image']         ?? '',
            (int)($d['sort_order'] ?? 0),
            $d['status']        ?? 'active',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE inventory_slabs
            SET name = ?, material_type = ?, size = ?, quantity = ?, image = ?, sort_order = ?, status = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $d['name'],
            $d['material_type'] ?? 'granite',
            $d['size']          ?? '3 CM',
            (int)($d['quantity'] ?? 0),
            $d['image']         ?? '',
            (int)($d['sort_order'] ?? 0),
            $d['status']        ?? 'active',
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare("DELETE FROM inventory_slabs WHERE id = ?")->execute([$id]);
    }
}
