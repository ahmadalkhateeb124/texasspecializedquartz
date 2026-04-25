<?php

/**
 * SinkRepository — CRUD for the sinks table.
 */
class SinkRepository
{
    public const CATEGORIES = ['kitchen', 'bathroom', 'bar', 'laundry'];

    public function __construct(private PDO $pdo) {}

    /* ───────────────────── Reads ───────────────────── */

    public function all(): array
    {
        return $this->pdo->query("
            SELECT * FROM sinks
            ORDER BY category ASC, subcategory ASC, sort_order ASC, id ASC
        ")->fetchAll();
    }

    public function allActive(): array
    {
        return $this->pdo->query("
            SELECT * FROM sinks
            WHERE status = 'active'
            ORDER BY category ASC, sort_order ASC, id ASC
        ")->fetchAll();
    }

    /**
     * Returns sinks nested by category → subcategory → items[].
     * Useful for the public-facing catalog page.
     */
    public function groupedForPublic(): array
    {
        $rows = $this->allActive();
        $out  = [];
        foreach ($rows as $row) {
            $cat = $row['category'];
            $sub = $row['subcategory'] ?: 'Other';
            $out[$cat][$sub][] = $row;
        }
        return $out;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sinks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function distinctSubcategories(string $category): array
    {
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT subcategory FROM sinks
            WHERE category = ? AND subcategory != ''
            ORDER BY subcategory ASC
        ");
        $stmt->execute([$category]);
        return array_column($stmt->fetchAll(), 'subcategory');
    }

    /* ───────────────────── Writes ───────────────────── */

    public function create(array $d): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO sinks (name, model, image, category, subcategory, sort_order, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $d['name'],
            $d['model']       ?? '',
            $d['image']       ?? '',
            $d['category']    ?? 'kitchen',
            $d['subcategory'] ?? '',
            (int)($d['sort_order'] ?? 0),
            $d['status']      ?? 'active',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE sinks
            SET name = ?, model = ?, image = ?, category = ?, subcategory = ?, sort_order = ?, status = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $d['name'],
            $d['model']       ?? '',
            $d['image']       ?? '',
            $d['category']    ?? 'kitchen',
            $d['subcategory'] ?? '',
            (int)($d['sort_order'] ?? 0),
            $d['status']      ?? 'active',
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->pdo->prepare("DELETE FROM sinks WHERE id = ?")->execute([$id]);
    }
}
